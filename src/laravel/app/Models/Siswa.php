<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Siswa extends Model
{
    //
    public static function booted()
    {
        
        static::deleted(function($siswa) {
            $img_url=$siswa->avatar_url;
            if(Storage::disk('public')->exists($img_url)==false) return;
            Storage::disk('public')->delete($img_url);
        });
        
    }
}
