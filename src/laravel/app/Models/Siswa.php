<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Siswa extends Model
{
    protected $table='siswa';
    protected $guarded=['id', 'created_at', 'updated_at'];
    //
    public static function booted()
    {
        
        static::deleted(function($siswa) {
            $img_url=$siswa->avatar_url;
            if(Storage::disk('public')->exists($img_url)==false) return;
            Storage::disk('public')->delete($img_url);
        });
        
    }
    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'id_siswa');
    }

    public function kuesionerResults()
    {
        return $this->hasMany(Dass21Hasil::class, 'id_siswa');
    }
}
