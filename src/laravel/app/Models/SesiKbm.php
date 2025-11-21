<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class SesiKbm extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    public static function booted()
    {
        static::created(function($kbm) {
            
        });
        static::deleting(function($kbm) {
            $kbm->kelas_libur()->detach();
            $kbm->log_harians()->delete();
        });
    }
    public function log_harians() : HasMany
    {
        return $this->hasMany(LogHarian::class, 'id_sesi_kbm');
    }
    public function kelas_libur() : BelongsToMany
    {
        return $this->belongsToMany(Kelas::class, 'kbm_kelas_liburs', 'id_kbm', 'id_kelas');
    }
    public function create_log_siswa()
    {
        $kelas_libur=$this->kelas_libur()->pluck('kelas.id')->toArray();
        $siswas=Siswa::where('tingkat', $this->tingkat)
        ->where('kelulusan', false)
        ->whereNotIn('id_kelas', $kelas_libur)
        ->get();
        $data=[];
        foreach($siswas as $s)
        {
            array_push($data,[
                'id_kbm'=>$this->id,
                'id_siswa'=>$s->nisn
            ]);
        }
        LogHarian::insert($data);
    }
}
