<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class GuruSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=
        [
            'nip'=>'12345678',
            'nama_lengkap'=>'Joko Sujiwo',
            'alamat'=>'semarang',
            'gender'=>true,
            'tgl_lahir'=>'1980-02-22'
        ];
        $user=
        [
            'username'=>'guru',
            'password'=>'12345678',
            'email'=>'role@example.com',
            'role'=>'guru'
        ];

        Guru::create($data);
        User::create($user);
    }
}
