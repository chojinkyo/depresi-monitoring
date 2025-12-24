<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Presensi;
use App\Models\Diary;
use App\Models\Dass21Hasil;
use Carbon\Carbon;

class BadMoodStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create User & Siswa "Budi Galau"
        $username = 'budigalau';
        if (!User::where('username', $username)->exists()) {
            $user = User::create([
                'username' => $username,
                'email' => 'galau@example.com',
                'password' => '12345678',
                'role' => 'siswa'
            ]);

            $siswa = Siswa::create([
                'nama_lengkap' => 'Budi Galau',
                'nisn' => '999888777',
                'gender' => 1, // Laki-laki
                'tanggal_lahir' => '2005-01-01',
                'alamat' => 'Jl. Kesedihan No. 1',
                'id_user' => $user->id,
                'id_thak_masuk' => 1, 
                'status' => true
            ]);

            // 2. Generate 14 days of BAD MOOD data
            for ($i = 13; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->setTime(rand(7, 9), rand(0, 59));
                
                // Create Presensi
                $presensi = Presensi::create([
                    'id_siswa' => $siswa->id,
                    'id_thak' => 1, 
                    'status' => 'H',
                    'created_at' => $date,
                    'updated_at' => $date
                ]);

                // Create Diary with Negative Mood
                $moods = ['sadness', 'sadness', 'sadness', 'fear']; 
                $mood = $moods[array_rand($moods)];
                
                // JSON format for prediction
                $predJson = json_encode([
                    'predicted' => $mood,
                    'confidence' => 0.95
                ]);

                Diary::create([
                    'id_presensi' => $presensi->id,
                    'swafoto' => 'dummy/path.jpg', // Corrected column name
                    'swafoto_pred' => $predJson,
                    'catatan' => 'Saya merasa sangat sedih hari ini...',
                    'catatan_pred' => 'terindikasi depresi', // Dummy text prediction
                    'catatan_ket' => 'Perlu perhatian', // Dummy ket
                    'judul_perasaan' => 'Sangat Sedih', // Added manual text
                    'emoji' => 4, 
                    'created_at' => $date,
                    'updated_at' => $date
                ]);
            }

            // 3. Generate DASS-21 Result (High Depression)
            // Questions 2, 4, 9, 12, 15, 16, 20 are Depression. Value 3 = Very High.
            $answers = [];
            for ($i = 0; $i < 21; $i++) {
                $val = 0;
                // Depression indices
                if (in_array($i, [2, 4, 9, 12, 15, 16, 20])) {
                    $val = 3;
                }
                // Anxiety (some high)
                elseif (in_array($i, [1, 3, 6, 8])) {
                    $val = 2;
                }
                
                $answers[] = ['question_index' => $i, 'value' => $val];
            }

            Dass21Hasil::create([
                'id_siswa' => $siswa->id,
                'answers' => $answers,
                'total_score' => 0, // Calculated on fly usually
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            
            $this->command->info("Siswa 'Budi Galau' created with 14 days of bad mood and specific DASS-21 results.");
        } else {
            $this->command->info("Siswa 'Budi Galau' already exists.");
        }
    }
}
