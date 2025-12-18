<?php

namespace App\Http\Controllers\App\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use Illuminate\Support\Facades\Auth;

class StatistikController extends Controller
{
    public function index()
    {
        $siswa = Auth::user()->siswa;
        $id_siswa = $siswa->id;

        // Check today's attendance
        $todayPresensi = Presensi::where('id_siswa', $id_siswa)
            ->whereDate('created_at', now()->today())
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
        $endDate = now();
        $startDate = now()->subDays(13); // 14 days including today

        $moodData = Presensi::where('id_siswa', $id_siswa)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->with('diary')
            ->orderBy('created_at')
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
                    'date' => $presensi->created_at->format('d M'),
                    'emoji' => $moodVal,
                    'label' => $prediction
                ];
            });

        // Fill missing dates with null or previous value if needed (optional, but good for chart)
        // For now, let's just pass the data we have. Chart.js can handle gaps or we can fill them in JS.

        return view('siswa.statistik', compact('isTodayPresent', 'stats', 'moodData'));
    }
}
