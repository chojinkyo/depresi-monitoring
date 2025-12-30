<?php

namespace App\Http\Controllers;

use App\Models\PresensiLibur;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Storage;

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
        if (!$user || !$user->siswa) {
            return redirect()->route('login');
        }

        $id_siswa = $user->siswa->id;
        
        // Mood Chart Data (Last 14 Days)
        $endDate = now();
        $startDate = now()->subDays(13); // 14 days including today

        $moodData = \App\Models\Presensi::where('id_siswa', $id_siswa)
            ->whereDate('waktu', '>=', $startDate)
            ->whereDate('waktu', '<=', $endDate)
            ->with('diary')
            ->orderBy('waktu')
            ->get()
            ->map(function ($presensi) {
                $prediction = null;
                if ($presensi->diary && $presensi->diary->swafoto_pred) {
                    try {
                        $predJson = json_decode($presensi->diary->swafoto_pred);
                        if (isset($predJson->predicted)) {
                            $prediction = $predJson->predicted;
                        }
                    } catch (\Exception $e) {
                         // Keep null
                    }
                }

                $moodVal = null;
                // Map prediction to 1-6 scale
                if ($prediction) {
                    switch(strtolower($prediction)) {
                        case 'anger': $moodVal = 1; break;
                        case 'disgust': $moodVal = 2; break;
                        case 'fear': $moodVal = 3; break;
                        case 'sadness': $moodVal = 4; break;
                        case 'surprise': $moodVal = 5; break;
                        case 'happy': $moodVal = 6; break;
                    }
                }

                return [
                    'date' => Carbon::parse($presensi->waktu)->format('d M'),
                    'emoji' => $moodVal,
                    'label' => $prediction
                ];
            });

        // Determine Most Frequent Mood (last 14 days)
        $validMoods = $moodData->pluck('emoji')->filter();
        $averageMood = 0; 
        $moodLabel = '-';
        
        if ($validMoods->isNotEmpty()) {
            $counts = array_count_values($validMoods->toArray());
            arsort($counts);
            $mostFrequentVal = array_key_first($counts);
            
             switch($mostFrequentVal) {
                case 1: $moodLabel = 'Marah 😠'; break;
                case 2: $moodLabel = 'Jijik 🤢'; break;
                case 3: $moodLabel = 'Takut 😨'; break;
                case 4: $moodLabel = 'Sedih 😢'; break;
                case 5: $moodLabel = 'Terkejut 😲'; break;
                case 6: $moodLabel = 'Senang 😊'; break;
            }
            // Set averageMood to something valid so the view doesn't break, 
            // though it's technically "mode" now.
            $averageMood = $mostFrequentVal; 
        }

        // Calculate Attendance Percentage (Current Month)
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        $attendanceStats = \App\Models\Presensi::where('id_siswa', $id_siswa)
            ->whereMonth('waktu', $currentMonth)
            ->whereYear('waktu', $currentYear)
            ->get();

        $totalSessions = $attendanceStats->count();
        $presentCount = $attendanceStats->where('status', 'H')->count(); // Assuming 'H' is Hadir
        
        // If we want to include Izin/Sakit as "attending" or partially, usually only H is 100%. 
        // Let's stick to H for now or strictly "Kehadiran" usually implies presence. 
        // If 'totalSessions' only includes days where data exists.
        
        $attendancePercentage = $totalSessions > 0 ? round(($presentCount / $totalSessions) * 100) : 0;

        // Placeholders for Grades and Rank
        $averageGrade = '-';
        $rank = '-';

        return view('dashboard.siswa', compact('moodData', 'averageMood', 'moodLabel', 'attendancePercentage', 'averageGrade', 'rank'));
    }
    public function guruDashboard()
    {
        return view('dashboard.guru');
    }
}
