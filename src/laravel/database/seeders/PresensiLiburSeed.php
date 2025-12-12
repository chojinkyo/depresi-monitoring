<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PresensiLiburSeed extends Seeder
{
    public function run()
    {
        // Daftar hari libur nasional Indonesia (tanggal & bulan)
        $holidays = [
            ['ket' => 'Tahun Baru Masehi', 'd' => 1, 'm' => 1],
            ['ket' => 'Isra Mi\'raj', 'd' => 27, 'm' => 1],
            ['ket' => 'Tahun Baru Imlek', 'd' => 1, 'm' => 2],
            ['ket' => 'Hari Raya Nyepi', 'd' => 29, 'm' => 3],
            ['ket' => 'Wafat Isa Almasih', 'd' => 18, 'm' => 4],
            ['ket' => 'Hari Buruh', 'd' => 1, 'm' => 5],
            ['ket' => 'Kenaikan Isa Almasih', 'd' => 29, 'm' => 5],
            ['ket' => 'Hari Lahir Pancasila', 'd' => 1, 'm' => 6],
            ['ket' => 'Idul Adha', 'd' => 6, 'm' => 6],
            ['ket' => 'Tahun Baru Hijriyah', 'd' => 26, 'm' => 6],
            ['ket' => 'Hari Kemerdekaan RI', 'd' => 17, 'm' => 8],
            ['ket' => 'Maulid Nabi', 'd' => 4, 'm' => 9],
            ['ket' => 'Natal', 'd' => 25, 'm' => 12],
        ];

        foreach ($holidays as $h) {

            // 🟦 1) Kombinasi jenjang 1,2,3
            DB::table('presensi_libur')->insert([
                'ket' => $h['ket'],
                'tanggal_mulai' => $h['d'],
                'tanggal_selesai' => $h['d'],
                'bulan_mulai' => $h['m'],
                'bulan_selesai' => $h['m'],
                'jenjang' => json_encode([1,2,3]),
                'id_author' => 1,
            ]);

            // 🟩 2) Kombinasi dua jenjang
            $twoCombinations = [
                [1,2],
                [1,3],
                [2,3]
            ];

            foreach ($twoCombinations as $combo) {
                DB::table('presensi_libur')->insert([
                    'ket' => $h['ket'],
                    'tanggal_mulai' => $h['d'],
                    'tanggal_selesai' => $h['d'],
                    'bulan_mulai' => $h['m'],
                    'bulan_selesai' => $h['m'],
                    'jenjang' => json_encode($combo),
                    'id_author' => 1,
                ]);
            }

            // 🟧 3) Kombinasi satu jenjang
            DB::table('presensi_libur')->insert([
                'ket' => $h['ket'],
                'tanggal_mulai' => $h['d'],
                'tanggal_selesai' => $h['d'],
                'bulan_mulai' => $h['m'],
                'bulan_selesai' => $h['m'],
                'jenjang' => json_encode([1]),
                'id_author' => 1,
            ]);
        }
    }
}
