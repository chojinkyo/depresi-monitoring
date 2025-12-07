<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diary extends Model
{
    protected $table = 'diary';

    protected $fillable = [
        'id_presensi',
        'catatan',
        'emoji',
        'swafoto',
        'swafoto_pred',
        'catatan_pred',
        'catatan_ket'
    ];
}
