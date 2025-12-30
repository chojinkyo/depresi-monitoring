<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class Diary extends Model
{
    //
    protected $guarded = ['id'];
    protected $table='diary';
    protected $casts=['waktu'=>'datetime', 'waktu_string'=>'datetime'];
    public $timestamps = false;

    protected static function booted()
    {
        static::addGlobalScope('locale_id', function (Builder $builder) {
            $builder->getQuery()->connection
                ->statement("SET lc_time_names = 'id_ID'");
        });
    }
    public function attendance() : BelongsTo
    {
        return $this->belongsTo(Presensi::class, 'id_presensi', 'id');
    }

    public static function getMentalHealthData($year, $students, $range)
    {
        $base=DB::table('diary')
            ->join('presensi', 'presensi.id', '=', 'diary.id_presensi')
            ->selectRaw("
                diary.*, 
                DATE_FORMAT(diary.waktu, '%d %M %Y %H:%i') AS waktu_string,
                presensi.id_siswa, presensi.id_thak,
                ROW_NUMBER() OVER (
                    PARTITION BY presensi.id_siswa
                    ORDER BY diary.waktu DESC
                ) AS rn
            ")
            ->where('id_thak', $year)
            ->whereIn('id_siswa', $students);
       
        $data=DB::table(DB::raw("({$base->toSql()}) as a"))
            ->mergeBindings($base)
            ->where('rn', '<=', $range)
            ->orderByDesc('waktu')
            ->get()
            ->groupBy('id_siswa');
        
        return $data;
    }
}
