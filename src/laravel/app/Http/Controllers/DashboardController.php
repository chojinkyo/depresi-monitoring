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
                    'date' => $presensi->created_at->format('d M'),
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
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
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

    }
}
