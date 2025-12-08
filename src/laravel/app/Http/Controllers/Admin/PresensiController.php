<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class PresensiController extends Controller
{
    public function index()
    {
        $currentAcademicYear=TahunAkademik::where('current', true)->first();
        $students=Siswa::select(['id', 'nisn', 'nama_lengkap'])
        ->paginate(10);

        $sub1=DB::table('presensi')
        ->selectRaw("
            id_siswa,
            COUNT(id) as total_presensi,
            SUM(CASE WHEN status='H' THEN 1 ELSE 0 END) as total_hadir,
            SUM(CASE WHEN status='A' THEN 1 ELSE 0 END) as total_alpha
        ")
        ->where('id_thak', $currentAcademicYear->id)
        ->whereIn('id_siswa', $students->pluck('id')->toArray())
        ->groupBy('id_siswa');
        $sub2=DB::table(DB::raw("({$sub1->toSql()}) as a"))
        ->mergeBindings($sub1)
        ->selectRaw("
            a.id_siswa,
            a.total_presensi,
            a.total_hadir,
            a.total_alpha,
            (a.total_presensi - (a.total_hadir+a.total_alpha)) as total_ijin_sakit,
            ROUND((a.total_hadir/a.total_presensi) * 100, 2) as persen_hadir,
            ROUND((a.total_alpha/a.total_presensi) * 100, 2) as persen_alpha
        ");

        $attendances=DB::table(DB::raw("({$sub2->toSql()}) as b"))
        ->mergeBindings($sub2)
        ->selectRaw("
            *,
            ROUND(100 - (persen_hadir + persen_alpha), 2) as persen_ijin_sakit
        ")
        ->get()
        ->keyBy('id_siswa');

        $studentAttendances=$students->map(function($student) use($attendances) {
            $student->presensi=$attendances->get($student->id) ?? null;
            return $student;
        });
        
        // dd($studentAttendances);
        return view('admin.presensi.index', compact('studentAttendances', 'currentAcademicYear'));
        
    }
}
