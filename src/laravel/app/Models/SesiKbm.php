<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SesiKbm extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    public function log_harians() : HasMany
    {
        return $this->hasMany(LogHarian::class, 'id_sesi_kbm');
    }
}
