<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Siswa extends Model
{
    protected $primaryKey="nisn";
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [
        'created_at', 'updated_at'
    ];

    public static function booted()
    {
        static::deleted(function($siswa) {
            $siswa->user()->delete();
        });
        
    }
    public function User() : BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function TahunAngkatan() : BelongsTo
    {
        return $this->belongsTo(Angkatan::class, 'id_angkatan');
    }
}
