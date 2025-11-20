<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Angkatan extends Model
{
    protected $guarded=['id', 'created_at', 'updated_at'];
    public function siswas() : HasMany
    {
        return $this->hasMany(Siswa::class, 'id_angkatan');
    }
    public function tahun_mulai() : BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_mulai');
    }
}
