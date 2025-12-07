<?php

namespace Database\Seeders;

use App\Models\Siswa;
use App\Models\User;
use App\Models\TahunAkademik;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiswaSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create User
        $user = User::updateOrCreate(
            ['username' => 'siswa'],
            [
                'password' => '12345678', // Will be hashed by User model mutator if exists, or handled by Laravel
                'email' => 'siswa@example.com',
                'role' => 'siswa'
            ]
        );

        // Create or Get Tahun Akademik
        $tahunAkademik = TahunAkademik::firstOrCreate(
            ['nama_tahun' => '2024/2025'],
            [
                'tanggal_mulai' => '2024-07-01',
                'tanggal_selesai' => '2025-06-30',
                'current' => true,
                'status' => true
            ]
        );

        // Create Siswa
        Siswa::create([
            'nisn' => '1234567890',
            'nama_lengkap' => 'Siswa Teladan',
            'tanggal_lahir' => '2005-01-01',
            'alamat' => 'Jl. Pendidikan No. 1',
            'gender' => 1, // Laki-laki
            'status' => true,
            'id_user' => $user->id,
            'id_thak_masuk' => $tahunAkademik->id
        ]);
    }
}
