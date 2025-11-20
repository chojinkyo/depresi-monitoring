<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kelas extends Model
{
    protected $guarded=['id', 'created_at', 'updated_at'];
    public function kalender() : BelongsToMany
    {
        return $this->belongsToMany(KalenderAkademik::class, 'sesi_liburs', 'id_kelas', 'id_kalender')->withPivot('status');
    }
    public function kbm_libur() : BelongsToMany
    {
        return $this->belongsToMany(SesiKbm::class, 'kbm_kelas_liburs', 'id_kelas', 'id_kbm');
    }
}
