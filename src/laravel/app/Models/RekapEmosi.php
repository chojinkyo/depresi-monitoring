<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapEmosi extends Model
{
    protected $table = 'rekap_emosi';

    protected $fillable = [
        'tgl',
        'hasil',
        'rekap_emoji',
        'id_siswa'
    ];
}
