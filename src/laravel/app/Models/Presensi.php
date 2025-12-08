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
        'ket',
        'doc'
    ];
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

    public function diary()
    {
        return $this->hasOne(Diary::class, 'id_presensi');
    }
}
