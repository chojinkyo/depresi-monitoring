<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GuruSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek jika user sudah ada untuk menghindari duplikat
        if (!User::where('username', 'guru')->exists()) {
            User::create([
                'username' => 'guru',
                'password' => '12345678',
                'email' => 'guru@example.com',
                'role' => 'guru'
            ]);
        }
    }
}
