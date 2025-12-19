<?php

namespace App\Http\Controllers\Dashboard\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Presensi;

class GuruSiswaController extends Controller
{
    public function moodIndex()
    {
        // Fetch all students (simplified for now, ideally filter by class if Guru has class assignment)
        // Since Guru model is empty, we just fetch all students.
        
        $siswas = Siswa::with(['presensi' => function($query) {
            $query->latest()->limit(1)->with('diary');
        }])->get();

        // Process data for display
        $siswaData = $siswas->map(function($siswa) {
            $lastPresensi = $siswa->presensi->first();
            $latestMood = '-';
            $latestMoodLabel = '-';
            
            if ($lastPresensi && $lastPresensi->diary && $lastPresensi->diary->swafoto_pred) {
                 try {
                    $predJson = json_decode($lastPresensi->diary->swafoto_pred);
                    if (isset($predJson->predicted)) {
                        $latestMoodLabel = $predJson->predicted;
                        
                        // Emoji mapping
                        switch(strtolower($latestMoodLabel)) {
                             case 'anger': $latestMood = '😠'; break;
                             case 'disgust': $latestMood = '🤢'; break;
                             case 'fear': $latestMood = '😨'; break;
                             case 'sadness': $latestMood = '😢'; break;
                             case 'surprise': $latestMood = '😲'; break;
                             case 'happy': $latestMood = '😊'; break;
                             default: $latestMood = '😐';
                        }
                    }
                } catch (\Exception $e) { }
            }

            return [
                'id' => $siswa->id,
                'nama' => $siswa->nama_lengkap,
                // 'kelas' => $siswa->kelas ? $siswa->kelas->nama : '-', // Assuming relationship exists, check later
                'last_update' => $lastPresensi ? $lastPresensi->created_at->format('d M Y H:i') : '-',
                'mood_emoji' => $latestMood,
                'mood_label' => $latestMoodLabel
            ];
        });

        return view('guru.mood.index', compact('siswaData'));
    }

    public function nilaiIndex()
    {
        return view('guru.nilai.index');
    }
}
