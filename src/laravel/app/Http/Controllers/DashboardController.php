<?php

namespace App\Http\Controllers;

use App\Models\PresensiLibur;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Storage;
use App\Models\Siswa;
use App\Models\Diary;
use App\Models\Presensi;
use App\Models\TahunAkademik;

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
    
    private function getCalendarDays($month, $year)
    {

        $start=Carbon::create($year, $month, 1);
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
    private function getSchedules()
    {
        $path="data/config/konfigurasi_jadwal_harian.json";
        if(Storage::exists($path))
        {
            $file=Storage::get($path);
            $content=json_decode($file);
            return $content;
        }
        return [];
    }
    public function getDiaryConfig()
    {
        $path="data/config/konfigurasi_rekap_mental.json";
        if(Storage::exists($path))
        {
            $file=Storage::get($path);
            $content=json_decode($file, true);
            return $content;
        }
        return [];
    }
    
    public function adminDashboard(Request $request)
    {
        $month=$request->input('month') ?? now()->month;
        $year=now()->year;
        $jenjang=(int) $request->input('jenjang') ?? 1;
        

        $schedules=$this->getSchedules();
        $calendars=$this->getCalendarDays($month, $year);
        $diaryConfig=$this->getDiaryConfig();
        $vacations=PresensiLibur::select(['id', 'ket', 'tanggal_mulai', 'tanggal_selesai', 'bulan_mulai', 'bulan_selesai'])->where('bulan_mulai', $month)
        ->orderBy('tanggal_mulai')
        ->get()
        ->toArray();
        
        // dd($vacations);
        return view('dashboard.admin', compact('calendars', 'vacations', 'schedules', 'diaryConfig'));
    }
    public function siswaDashboard()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user || !$user->siswa) 
            return redirect()->route('login');

        $academicYear=TahunAkademik::orderByRaw('(3 - current - status) ASC')->orderBy('nama_tahun', 'desc')->first()?->id;

        $path="data/config/konfigurasi_rekap_mental.json";
        if(!Storage::exists($path))
            throw new \Exception('File konfigurasi tidak ditemukan', 500);
        $config=Storage::get($path);
        $config=json_decode($config, true);
        $range=(int) $config['rentang'] ?? 1;
        $threshold=(int) $config['threshold'];

        $student=$user->siswa;
        $studentIds=[$student->id];
        $month=now()->month;
        

        // dd($students);
        $mentalHealthData=Diary::getMentalHealthData($academicYear, $studentIds, $range);
        $attendanceResults=Presensi::getAttendanceCalcThisMonth($academicYear, $studentIds);
        

        $mentalDetails = collect($mentalHealthData->get($student->id));
        $dpMeter = $mentalDetails->reduce(function($acc, $row) {
            $swafoto_pred=strtolower($row->swafoto_pred);
            $catatan_pred=strtolower($row->catatan_pred);
            $bool=($catatan_pred==='terindikasi depresi' && !in_array($swafoto_pred, ['happy', 'surprise']));
            return $acc + (int) $bool;
        }, 0);
        
        $percentage=0;
        $totals=$mentalDetails->count();
        
        $day1="";
        $day2="";
        if($totals > 0)
        {
            $percentage=($dpMeter / $totals) * 100;
            $day1=$mentalDetails->first()?->waktu;
            $day2=$mentalDetails->last()?->waktu;

            $day1=Carbon::parse($day1)->format('j F Y');
            $day2=Carbon::parse($day2)->format('j F Y');
        }

        $mentalResults=collect([
            'days'=>$totals,
            'depression_rate'=>$percentage,
            'time_range'=>"$day1 - $day2"
        ]);
        

       

        // return view('dashboard.siswa', compact('moodData', 'averageMood', 'moodLabel', 'attendancePercentage', 'averageGrade', 'rank'));
        return view('dashboard.siswa', compact('mentalResults', 'mentalDetails', 'attendanceResults'));
    }
    public function guruDashboard()
    {
        return view('dashboard.guru');
    }
}
