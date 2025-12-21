<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Presensi;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $academicYear=$request->has('year') ? $request->input('year') : TahunAkademik::orderByRaw('(3 - current - status) ASC')->orderBy('nama_tahun', 'desc')->first()?->id;
        $classesIds=$request->has('class') ? [$request->input('class')] : Kelas::select('id')->pluck('id')->toArray();
        $grades=$request->has('grade') ? [$request->input('grade')] : [1, 2, 3];

        $students=Siswa::query()
        ->matchClassHistory($classesIds, $academicYear, $grades)
        ->getClass($classesIds)
        ->select(['id', 'nisn', 'nama_lengkap', 'id_user'])
        ->paginate(10);
        
        [$results, $details]=Presensi::getAttendanceCalc($academicYear, $students->pluck('id')->toArray());
        $studentAttendances=$students->through(function($student) use($results, $details) {
            $result=$results->get($student->id) ?? null;
            $detail=$details?->get($student->id) ?? null;

            $student->presensi=collect([
                'result'=>$result,
                'details'=>$detail
            ]);

            return $student;
        });



        // dd($results);
        
        return view('admin.presensi.index', compact('studentAttendances'));
        
    }
    public function show(Request $request, $student, $year)
    {
        $limit=10;
        $page=$request->input('page') ?? 0;
        $presensi=Presensi::where('id_siswa', $student)->where('id_thak', $year)->orderBy('waktu')->skip($page*$limit)->limit($limit)->get();

        return response()->json(['response'=>['data'=>$presensi]], 200);
    }
}
