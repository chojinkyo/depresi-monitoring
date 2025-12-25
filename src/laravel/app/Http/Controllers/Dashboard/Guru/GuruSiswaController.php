<?php

namespace App\Http\Controllers\Dashboard\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Presensi;
use App\Models\Dass21Hasil;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GuruSiswaController extends Controller
{
    // DASS-21 Questions in Indonesian
    private const DASS21_QUESTIONS = [
        0 => ['text' => 'Saya merasa sulit untuk beristirahat', 'category' => 'Stress'],
        1 => ['text' => 'Saya menyadari mulut saya kering', 'category' => 'Anxiety'],
        2 => ['text' => 'Saya tidak dapat merasakan perasaan positif sama sekali', 'category' => 'Depression'],
        3 => ['text' => 'Saya mengalami kesulitan bernapas', 'category' => 'Anxiety'],
        4 => ['text' => 'Saya merasa sulit untuk memulai melakukan sesuatu', 'category' => 'Depression'],
        5 => ['text' => 'Saya cenderung bereaksi berlebihan terhadap situasi', 'category' => 'Stress'],
        6 => ['text' => 'Saya mengalami gemetar (misalnya di tangan)', 'category' => 'Anxiety'],
        7 => ['text' => 'Saya merasa menggunakan banyak energi untuk cemas', 'category' => 'Stress'],
        8 => ['text' => 'Saya khawatir akan situasi di mana saya mungkin panik', 'category' => 'Anxiety'],
        9 => ['text' => 'Saya merasa tidak ada hal yang bisa diharapkan', 'category' => 'Depression'],
        10 => ['text' => 'Saya merasa gelisah', 'category' => 'Stress'],
        11 => ['text' => 'Saya merasa sulit untuk bersantai', 'category' => 'Stress'],
        12 => ['text' => 'Saya merasa sedih dan tertekan', 'category' => 'Depression'],
        13 => ['text' => 'Saya tidak sabar dengan hal-hal yang menghalangi saya', 'category' => 'Stress'],
        14 => ['text' => 'Saya merasa hampir panik', 'category' => 'Anxiety'],
        15 => ['text' => 'Saya tidak bisa merasa antusias tentang apapun', 'category' => 'Depression'],
        16 => ['text' => 'Saya merasa tidak berharga sebagai manusia', 'category' => 'Depression'],
        17 => ['text' => 'Saya merasa mudah tersinggung', 'category' => 'Stress'],
        18 => ['text' => 'Saya menyadari detak jantung saya meski tidak melakukan aktivitas fisik', 'category' => 'Anxiety'],
        19 => ['text' => 'Saya merasa takut tanpa alasan yang jelas', 'category' => 'Anxiety'],
        20 => ['text' => 'Saya merasa hidup ini tidak bermakna', 'category' => 'Depression'],
    ];

    /**
     * Display list of students with latest mood (main page)
     */
    /**
     * Display list of students with latest mood (main page)
     * Filtered: Only show students with DOMINANT NEGATIVE mood in last 14 days
     */
    public function moodIndex()
    {
        $siswas = Siswa::with([
            // 'presensi' => function($query) {
            //     return $query->latest('waktu')->limit(1)->with('diary');
            // },
            'recaps' => function($query) {
                return $query->latest('created_at')->first();
            }
        ])
        ->where('need_selfcare', true)
        ->orWhere('is_depressed', true)
        ->get();
        
//         $startDate = Carbon::now()->subDays(13);
//         $endDate = Carbon::now();

//         // Eager load presensi for last 14 days to calculate dominant mood
//         $siswas = Siswa::with(['presensi' => function($query) use ($startDate, $endDate) {
//             $query->whereDate('created_at', '>=', $startDate)
//                   ->whereDate('created_at', '<=', $endDate)
//                   ->with('diary')
//                   ->orderBy('created_at', 'desc');
//         }])->get();

//         $filteredSiswas = $siswas->filter(function($siswa) {
//             $moodCounts = ['negative' => 0, 'positive' => 0];
            
//             foreach($siswa->presensi as $presensi) {
//                 if ($presensi->diary && $presensi->diary->swafoto_pred) {
//                     try {
//                         $predJson = json_decode($presensi->diary->swafoto_pred);
//                         if (isset($predJson->predicted)) {
//                             $mood = strtolower($predJson->predicted);
//                             if (in_array($mood, ['anger', 'disgust', 'fear', 'sadness'])) {
//                                 $moodCounts['negative']++;
//                             } elseif (in_array($mood, ['happy', 'surprise'])) {
//                                 $moodCounts['positive']++;
//                             }
//                         }
//                     } catch (\Exception $e) { }
//                 }
//             }
            
//             // Condition: Negative moods > Positive moods (Dominantly Bad Mood)
//             // Or if equal but non-zero? Let's stick to Bad > Good for now.
//             // Adjust threshold as needed (e.g., > 30% negative).
//             // User request: "kalo moodnya selama 14 hari jelek" -> strict reading implies majority bad.
//             return $moodCounts['negative'] > $moodCounts['positive'] && $moodCounts['negative'] > 0;
//         });

//         $siswaData = $filteredSiswas->map(function($siswa) {
//             $lastPresensi = $siswa->presensi->first(); // Since we ordered desc in eager load
        $siswaData = $siswas->map(function($siswa) {
            $lastPresensi = $siswa->presensi()->with('diary');
            $lastPresensi = $siswa->recaps->count() > 0 ? $lastPresensi->where('waktu', '<=', $siswa->recaps->first()?->created_at) : $lastPresensi;
            $lastPresensi = $lastPresensi->latest('waktu')->first();
            $latestMood = '-';
            $latestMoodLabel = '-';
            
            if ($lastPresensi && $lastPresensi->diary && $lastPresensi->diary->swafoto_pred) {
                // try {
                //     $predJson = json_decode($lastPresensi->diary->swafoto_pred);
                //     if (isset($predJson->predicted)) {
                //         $latestMoodLabel = $predJson->predicted;
                //         $latestMood = $this->getEmoji($latestMoodLabel);
                //     }
                // } catch (\Exception $e) { }

                $latestMoodLabel=$lastPresensi->diary->swafoto_pred;
                
            }

            return [
                'id' => $siswa->id,
                'nama' => $siswa->nama_lengkap,
                'last_update' => $lastPresensi ? $lastPresensi->waktu->format('d M Y H:i') : '-',
                'mood_emoji' => $latestMoodLabel,
                'mood_label' => $latestMoodLabel
            ];
        });

        return view('guru.mood.index', compact('siswaData'));
    }

    /**
     * Display detailed mood report for a specific student
     */
    public function moodDetail($siswaId)
    {
        $siswa = Siswa::findOrFail($siswaId);
        
        // Get 14-day mood data
        $startDate = Carbon::now()->subDays(13);
        $endDate = Carbon::now();
        
        $presensiData = Presensi::where('id_siswa', $siswaId)
            ->whereDate('waktu', '>=', $startDate)
            ->whereDate('waktu', '<=', $endDate)
            ->with('diary')
            ->orderBy('waktu', 'desc')
            ->get();

        $moodHistory = $presensiData->map(function($presensi) {
            $emotionLabel = '-';
            $emotionEmoji = '-';
            $predictionJson = '-';
            
            if ($presensi->diary && $presensi->diary->swafoto_pred) {
                // $predictionJson = $presensi->diary->swafoto_pred;
                // try {
                //     $pred = json_decode($predictionJson);
                //     if (isset($pred->predicted)) {
                //         $emotionLabel = $pred->predicted;
                //         $emotionEmoji = $this->getEmoji($emotionLabel);
                //     }
                // } catch (\Exception $e) {}

                $emotionLabel=$presensi->diary->swafoto_pred;
            }

            return [
                'tanggal' => $presensi->waktu->format('d M Y'),
                'waktu' => $presensi->waktu->format('H:i'),
                'status' => $presensi->status,
                'manual_mood' => $presensi->diary->judul_perasaan ?? '-',
                'camera_pred' => $emotionLabel,
                'text_pred' => ucfirst($presensi->diary->catatan_pred ?? '-'),
                'catatan' => $presensi->diary->catatan ?? '-',
            ];
        });

        // Get latest DASS-21 result
        $dassResult = Dass21Hasil::where('id_siswa', $siswaId)
            ->latest()
            ->first();

        $dassScores = null;
        $dassAnswers = [];
        
        if ($dassResult) {
            $dassScores = $dassResult->calculateScores();
            $dassScores['depression_label'] = $this->getDepressionLabel($dassScores['depression']);
            $dassScores['anxiety_label'] = $this->getAnxietyLabel($dassScores['anxiety']);
            $dassScores['stress_label'] = $this->getStressLabel($dassScores['stress']);
            $dassScores['date'] = $dassResult->created_at->format('d M Y H:i');

            // Format answers with question text
            if (is_array($dassResult->answers)) {
                foreach ($dassResult->answers as $ans) {
                    $qIndex = is_array($ans) ? $ans['question_index'] : $ans->question_index;
                    $value = is_array($ans) ? $ans['value'] : $ans->value;
                    
                    $dassAnswers[] = [
                        'no' => $qIndex + 1,
                        'question' => self::DASS21_QUESTIONS[$qIndex]['text'] ?? "Pertanyaan $qIndex",
                        'category' => self::DASS21_QUESTIONS[$qIndex]['category'] ?? '-',
                        'answer' => $value,
                        'answer_text' => $this->getAnswerText($value),
                    ];
                }
                // Sort by question number
                usort($dassAnswers, fn($a, $b) => $a['no'] <=> $b['no']);
            }
        }

        // dd($moodHistory);
        return view('guru.mood.detail', compact('siswa', 'moodHistory', 'dassScores', 'dassAnswers'));
    }

    /**
     * Export mood data as CSV
     */
    /**
     * Export mood data as PDF
     */
    public function exportMoodPdf($siswaId)
    {
        $siswa = Siswa::findOrFail($siswaId);
        
        // Get 14-day mood data similar to details view logic
        $startDate = Carbon::now()->subDays(13);
        $endDate = Carbon::now();
        
        $presensiData = Presensi::where('id_siswa', $siswaId)
            ->whereDate('waktu', '>=', $startDate)
            ->whereDate('waktu', '<=', $endDate)
            ->with('diary')
            ->orderBy('waktu', 'desc')
            ->get();

        // Transform data for view
        $moodHistory = $presensiData->map(function($presensi) {
            $emotionLabel = '-';
            $emotionEmoji = '-';
            $textPred = '-';
            $manualMood = '-';
            
            if ($presensi->diary) {
                // Camera Prediction
                if ($presensi->diary->swafoto_pred) {
                    try {
                        $pred = json_decode($presensi->diary->swafoto_pred);
                        if (isset($pred->predicted)) {
                            $emotionLabel = ucfirst($pred->predicted);
                            $emotionEmoji = $this->getEmoji($emotionLabel);
                        }
                    } catch (\Exception $e) {}
                }

                // Text Prediction
                $textPred = $presensi->diary->catatan_pred ?? '-';

                // Manual Mood (Text Input)
                $manualMood = $presensi->diary->judul_perasaan ?? '-';
            }

            return [
                'tanggal' => $presensi->waktu->translatedFormat('d M Y'),
                'waktu' => $presensi->waktu->format('H:i'),
                'status' => $presensi->status,
                'camera_pred' => $emotionLabel,
                'camera_emoji' => '', // Removed emoji
                'text_pred' => ucfirst($textPred),
                'manual_mood' => $manualMood,
                'catatan' => $presensi->diary->catatan ?? '-',
            ];
        });

        // Get DASS-21 Data
        $dassResult = Dass21Hasil::where('id_siswa', $siswaId)->latest()->first();
        $dassScores = null;
        if ($dassResult) {
            $dassScores = $dassResult->calculateScores();
            $dassScores['depression_label'] = $this->getDepressionLabel($dassScores['depression']);
            $dassScores['anxiety_label'] = $this->getAnxietyLabel($dassScores['anxiety']);
            $dassScores['stress_label'] = $this->getStressLabel($dassScores['stress']);
            $dassScores['date'] = $dassResult->created_at->translatedFormat('d M Y H:i');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('guru.mood.pdf', compact('siswa', 'moodHistory', 'dassScores'));
        return $pdf->download('Laporan_Mood_' . str_replace(' ', '_', $siswa->nama_lengkap) . '.pdf');
    }

    /**
     * Placeholder page for nilai (grades)
     */
    public function nilaiIndex()
    {
        return view('guru.nilai.index');
    }

    // Helper methods
    private function getEmoji(string $label): string
    {
        return match(strtolower($label)) {
            'anger' => '😠',
            'disgust' => '🤢',
            'fear' => '😨',
            'sadness' => '😢',
            'surprise' => '😲',
            'happy' => '😊',
            default => '😐',
        };
    }

    private function getDepressionLabel(int $score): string
    {
        if ($score <= 9) return 'Normal';
        if ($score <= 13) return 'Ringan';
        if ($score <= 20) return 'Sedang';
        if ($score <= 27) return 'Parah';
        return 'Sangat Parah';
    }

    private function getAnxietyLabel(int $score): string
    {
        if ($score <= 7) return 'Normal';
        if ($score <= 9) return 'Ringan';
        if ($score <= 14) return 'Sedang';
        if ($score <= 19) return 'Parah';
        return 'Sangat Parah';
    }

    private function getStressLabel(int $score): string
    {
        if ($score <= 14) return 'Normal';
        if ($score <= 18) return 'Ringan';
        if ($score <= 25) return 'Sedang';
        if ($score <= 33) return 'Parah';
        return 'Sangat Parah';
    }

    private function getAnswerText(int $value): string
    {
        return match($value) {
            0 => 'Tidak pernah',
            1 => 'Kadang-kadang',
            2 => 'Sering',
            3 => 'Hampir selalu',
            default => '-',
        };
    }
}
