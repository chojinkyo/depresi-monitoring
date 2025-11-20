<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class KalenderAkademik extends Model
{
    protected $guarded=['id', 'created_at', 'updated_at'];

    public function kelas() : BelongsToMany
    {
        return $this->belongsToMany(Kelas::class, 'sesi_liburs', 'id_kalender', 'id_kelas')->withPivot('status');
    }
    public function tahun_ajaran() : BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran');
    }
}
