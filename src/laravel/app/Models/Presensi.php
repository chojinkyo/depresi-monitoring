<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    protected $table = 'presensi';

    protected $fillable = [
        'id_siswa',
        'id_thak',
        'status',
        'ket'
    ];
}
