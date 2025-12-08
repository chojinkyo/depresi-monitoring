<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TahunAkademikStoreRequest;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class TahunAkademikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $academicYears=TahunAkademik::paginate(10)->sortByDesc('nama_tahun')->sortBy(function($item) {
            $name=$item->nama_tahun;
            $power=(3-$item->current-$item->status);
            return [$power, $name];
        })->values();
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
        TahunAkademik::create($academicYearData);
        return redirect()->route('admin.tahun_akademik.index')
        ->with('status', 'Tahun akademik berhasil dibuat');
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
    public function update(Request $request, TahunAkademik $academicYear)
    {
        if($academicYear==null)
            return back()->with('error', 'Siswa tidak ditemukan!');
        $academicYearData=$request->validated();
        $academicYear->update($academicYearData);
        return redirect()->route('admin.tahun_akademik.index')
        ->with('status', 'Tahun akademik berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TahunAkademik $academicYear)
    {
        if($academicYear==null)
            return back()->with('error', 'Siswa tidak ditemukan!');
        $academicYear->delete();
        return redirect()->route('admin.tahun_akademik.index')
        ->with('status', 'Tahun akademik berhasil dihapus');
    }
}
