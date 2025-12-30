<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TahunAkademik extends Model
{
    protected $table="tahun_akademik";
    protected $guarded=['id'];
    public $timestamps=false;
    protected static function booted()
    {
        static::addGlobalScope('locale_id', function (Builder $builder) {
            $builder->getQuery()->connection
                ->statement("SET lc_time_names = 'id_ID'");
        });
    }

}
