<?php

namespace App\Http\Controllers\App\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\TahunAkademik;
use Illuminate\Support\Facades\Storage;
use App\Models\Diary;

class StatistikController extends Controller
{
    public function index()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user || !$user->siswa) 
            return redirect()->route('login');
        $siswa = Auth::user()->siswa;
        $id_siswa = $siswa->id;

        // Check today's attendance
        $todayPresensi = Presensi::where('id_siswa', $id_siswa)
            ->whereDate('waktu', now()->today())
            ->first();

        $isTodayPresent = $todayPresensi ? true : false;

        // Count history
        $history = Presensi::where('id_siswa', $id_siswa)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Default values
        $stats = [
            'H' => $history['H'] ?? 0,
            'I' => $history['I'] ?? 0,
            'S' => $history['S'] ?? 0,
            'A' => $history['A'] ?? 0,
        ];

       
        // Mood Chart Data (Last 14 Days)
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

        // Fill missing dates with null or previous value if needed (optional, but good for chart)
        // For now, let's just pass the data we have. Chart.js can handle gaps or we can fill them in JS.

        return view('siswa.statistik', compact('isTodayPresent', 'stats', 'mentalDetails', 'mentalResults'));
    }
}
