<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return 
        [
            new Middleware('auth', only : ['adminDashboard', 'guruDashboard']),
            new Middleware('auth:sanctum', only : ['siswaDashboard']),
            new Middleware('role:admin,guru', only : ['adminDashboard', 'guruDashboard']),
            new Middleware('role:siswa', only : ['siswaDashboard'])
        ];
    }
    public function adminDashboard()
    {
        return view('dashboard.admin');
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
