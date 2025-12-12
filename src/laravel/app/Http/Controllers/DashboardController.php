<?php

namespace App\Http\Controllers;

use App\Models\PresensiLibur;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return 
        [
            new Middleware('role:siswa', only : ['siswaDashboard']),
            new Middleware('auth:sanctum', only : ['siswaDashboard']),
            new Middleware('auth', only : ['adminDashboard', 'guruDashboard']),
            new Middleware('role:admin,guru', only : ['adminDashboard', 'guruDashboard']),
        ];
    }
    
    private function getCalendarDays()
    {
        $month=now()->month;
        $years=now()->year;

        $start=Carbon::create($years, $month, 1);
        $end=$start->copy()->endOfMonth();

        $index=0;
        $calendar=[];
        foreach(Carbon::parse($start)->toPeriod($end) as $date)
        {
            $day=$date->dayOfWeek;
            if($day==0 ||count($calendar)==0)
            {
                $index=count($calendar);
                array_push($calendar, []);
            }
            array_push($calendar[$index], ['date'=>$date->day,'day'=>$day]);
        }
        // dd($calendar);
        return $calendar;
    
    }
    
    public function admin()
    {
        $month=now()->month;
        $calendars=$this->getCalendarDays();
        $vacations=PresensiLibur::select(['ket', 'tanggal_mulai', 'tanggal_selesai', 'bulan_mulai', 'bulan_selesai'])->where('bulan_mulai', $month)->orderBy('tanggal_mulai')->get()->toArray();
        
        return view('dashboard.admin', compact('calendars', 'vacations'));
    }
    public function siswaDashboard()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user || !$user->siswa) {
            return redirect()->route('login');
        }

        $id_siswa = $user->siswa->id;
        
        // Mood Chart Data (Last 14 Days)
        $endDate = now();
        $startDate = now()->subDays(13); // 14 days including today

        $moodData = \App\Models\Presensi::where('id_siswa', $id_siswa)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->with('diary')
            ->orderBy('created_at')
            ->get()
            ->map(function ($presensi) {
                return [
                    'date' => $presensi->created_at->format('d M'),
                    'emoji' => $presensi->diary ? $presensi->diary->emoji : null,
                ];
            });

        // Calculate Average Mood (last 14 days)
        $validMoods = $moodData->pluck('emoji')->filter();
        $averageMood = $validMoods->isNotEmpty() ? round($validMoods->avg(), 1) : 0;
        
        // Determine Mood Label
        $moodLabel = '-';
        if ($averageMood > 4) $moodLabel = 'Sangat Baik 😊';
        elseif ($averageMood > 3) $moodLabel = 'Baik 🙂';
        elseif ($averageMood > 2) $moodLabel = 'Netral 😐';
        elseif ($averageMood > 1) $moodLabel = 'Kurang Baik 😟';
        elseif ($averageMood > 0) $moodLabel = 'Buruk 😢';

        return view('dashboard.siswa', compact('moodData', 'averageMood', 'moodLabel'));
    }
    public function guruDashboard()
    {

    }
}
