<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TahunAkademikStoreRequest;
use App\Http\Requests\Admin\TahunAkademikUpdateRequest;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TahunAkademikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $academicYears=TahunAkademik::selectRaw("
            *,
            DATE_FORMAT(tanggal_mulai, '%d %M %Y') AS tanggal_mulai,
            DATE_FORMAT(tanggal_selesai, '%d %M %Y') AS tanggal_selesai,
            SUBSTRING_INDEX(nama_tahun, '/', 1) AS tahun_mulai,
            SUBSTRING_INDEX(nama_tahun, '/', -1) AS tahun_akhir
        ")->orderByRaw('(3 - current - status) ASC')->orderBy('nama_tahun', 'desc')->paginate(10);

        // dd($academicYears);
        return view('admin.tahun_akademik.index', compact('academicYears'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TahunAkademikStoreRequest $request)
    {
        $academicYearData=$request->validated();
        $academicYearData=[
            ...array_diff_key($academicYearData, array_flip(['tahun_mulai', 'tahun_akhir'])),
            'nama_tahun'=>$academicYearData['tahun_mulai'].'/'.$academicYearData['tahun_akhir'],
        ];
        if(TahunAkademik::where('nama_tahun', $academicYearData['nama_tahun'])->exists())
        {
            throw ValidationException::withMessages(['periode'=>'periode should be unique']);
            return;
        }
        TahunAkademik::create($academicYearData);
        return redirect()->route('admin.tahun-akademik.index')
        ->with('success', [
            'icon'=>'success',
            'title'=>'Berhasil!',
            'text'=>'Tahun akademik berhasil dibuat!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(TahunAkademik $academicYear)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TahunAkademik $academicYear)
    {
        if($academicYear==null)
            return back()->with('error', 'Siswa tidak ditemukan!');
        return view('', compact('academicYear'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TahunAkademikUpdateRequest $request, TahunAkademik $tahunAkademik)
    {
        if($tahunAkademik==null)
            return back()->with('error', 'Siswa tidak ditemukan!');
        $academicYearData=$request->validated();
        $academicYearData=[
            ...array_diff_key($academicYearData, array_flip(['tahun_mulai', 'tahun_akhir'])),
            'nama_tahun'=>$academicYearData['tahun_mulai'].'/'.$academicYearData['tahun_akhir'],
        ];
        if($tahunAkademik->nama_tahun!=$academicYearData['nama_tahun'] && TahunAkademik::where('nama_tahun', $academicYearData['nama_tahun'])->where('id', '!=', $tahunAkademik->id)->exists())
        {
            throw ValidationException::withMessages(['periode'=>'periode should be unique']);
            return;
        }
        
        $tahunAkademik->update($academicYearData);
        return redirect()->route('admin.tahun-akademik.index')
        ->with('success', [
            'icon'=>'success',
            'title'=>'Berhasil!',
            'text'=>'Tahun akademik berhasil diupdate!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TahunAkademik $tahunAkademik)
    {
        if($tahunAkademik==null)
            return back()->with('error', 'Siswa tidak ditemukan!');
        $tahunAkademik->delete();
        return redirect()->route('admin.tahun-akademik.index')
        ->with('success', [
            'icon'=>'success',
            'title'=>'Berhasil!',
            'text'=>'Tahun akademik berhasil dihapus!'
        ]);
    }
}
