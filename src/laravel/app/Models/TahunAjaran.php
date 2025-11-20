<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TahunAjaran extends Model
{
    //
    protected $guarded=['id', 'created_at', 'updated_at'];
    public static function booted()
    {
        static::created(function($tahun_ajaran) {
            $angkatan_data=
            [
                'tahun'=>$tahun_ajaran->tahun_mulai,
                'tanggal_mulai'=>$tahun_ajaran->tanggal_mulai,
                'tanggal_akhir'=>$tahun_ajaran->tanggal_akhir,
                'id_tahun_mulai'=>$tahun_ajaran->id
            ];
            \App\Models\Angkatan::create($angkatan_data);
        });
        static::updated(function($tahun_ajaran) {
            $angkatan_data=
            [
                'tahun'=>$tahun_ajaran->tahun_mulai,
                'tanggal_mulai'=>$tahun_ajaran->tanggal_mulai,
                'tanggal_akhir'=>$tahun_ajaran->tanggal_akhir,
                'id_tahun_mulai'=>$tahun_ajaran->id
            ];
            $tahun_ajaran->angkatan->first()->update($angkatan_data);
        });
    }
    public function angkatan() : HasOne
    {
        return $this->hasOne(Angkatan::class, 'id_tahun_mulai');
    }
    public function siswas() : HasManyThrough
    {
        return $this->hasManyThrough(Siswa::class, Angkatan::class, 'id_tahun_mulai', 'id_angkatan');
    }
    public function kalenders() : HasMany
    {
        return $this->hasMany(KalenderAkademik::class, 'id_tahun_ajaran');
    }
}
