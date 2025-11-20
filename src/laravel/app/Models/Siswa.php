<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Siswa extends Model
{
    protected $primaryKey="nisn";
    protected $keyType='string';
    protected $guarded=['created_at', 'updated_at'];
    public $incrementing=false;

    public static function booted()
    {
        static::deleted(function($siswa) {
            $siswa->user()->delete();
        });
        
    }
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function tahun_angkatan() : BelongsTo
    {
        return $this->belongsTo(Angkatan::class, 'id_angkatan');
    }
}
