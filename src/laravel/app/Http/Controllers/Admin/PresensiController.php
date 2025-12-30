<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Presensi;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Models\Siswa;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        
        $results=Presensi::getAttendanceCalc($academicYear, $students->pluck('id')->toArray());
        $studentAttendances=$students->through(function($student) use($results) {
            $result=$results->get($student->id) ?? null;
            

            

            $student->presensi=collect([
                'result'=>$result,
            ]);

            return $student;
        });
        // dd($studentAttendances);
        return view('admin.presensi.index', compact('studentAttendances'));
        
    }
    public function sync()
    {
        
        
    }
    private function getConfFile()
    {
        $path="data/config/konfigurasi_jadwal_harian.json";
        if(!Storage::exists($path)) throw new Exception("File jadwal tidak ditemukan", 500);
        $json=Storage::get($path);
        $content=json_decode($json);
        return $content;

    }
    public function show(Request $request, $student)
    {
        $limit=$request->input('limit') ?? 10;
        $page=$request->input('page') ?? 0;
        $year=$request->input('year') ?? TahunAkademik::orderByRaw('(3 - current - status) ASC')->orderBy('nama_tahun', 'desc')->first()?->id;
        $presensi=Presensi::selectRaw("*, DATE_FORMAT(waktu, '%d %M %Y') AS date_string, DATE_FORMAT(waktu, '%H:%i') AS time_string, DATE_FORMAT(waktu, '%d %M %Y %H:%i') as waktu_string")
        ->where('id_siswa', $student)
        ->where('id_thak', $year)
        ->orderBy('waktu')
        ->skip($page*$limit)
        ->limit($limit)
        ->get();
        
        return response()->json(['response'=>['data'=>$presensi]], 200);
    }
}
