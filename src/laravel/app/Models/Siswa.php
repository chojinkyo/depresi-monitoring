<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Siswa extends Model
{
    protected $table='siswa';
    protected $guarded=['id', 'created_at', 'updated_at'];
    // protected $with=['kelasAktif'];
    protected $primaryKey = 'id';
    public static function booted()
    {
        // static::deleted(function($siswa) {
        //     $img_url=$siswa->avatar_url ?? "";
        //     if(Storage::disk('private')->exists($img_url)==false) return;
        //     Storage::disk('private')->delete($img_url);
        // });
    }
    public function riwayat_kelas() : HasMany
    {
        return $this->hasMany(RiwayatKelas::class, 'id_siswa');
    }
    public function riwayat_kelas_aktif() : HasOne
    {
        return $this->hasOne(RiwayatKelas::class, 'id_siswa')->where('active', true);
    }
    public function kelas() : BelongsToMany
    {
        return $this->belongsToMany(Kelas::class, 'riwayat_kelas', 'id_siswa', 'id_kelas');
    }
    public function kelas_aktif() : BelongsToMany
    {
        return $this->belongsToMany(Kelas::class, 'riwayat_kelas', 'id_siswa', 'id_kelas')->wherePivot("active", true)->limit(1);
    }
    public function getClassByAcademicYear($academiYearId)
    {
        return $this->riwayat_kelas()->where('id_thak', $academiYearId)?->first()?->kelas;
    }
    public function getKelasAktif()
    {
        return $this->kelas_aktif()?->first();
    }
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
