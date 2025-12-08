<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\KelasStoreRequest;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $classes=Kelas::orderBy('jenjang', 'asc')->orderBy('nama', 'asc')->paginate(10);
        return view('admin.kelas.index', compact('classes'));
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
    public function store(KelasStoreRequest $request)
    {
        $classData=$request->validated();
        Kelas::create($classData);
        return redirect()->route('admin.kelas.index')
        ->with('status', 'Kelas berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelas $class_)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelas $class_)
    {
        if($class_==null)
            return back()->with('status', 'Kelas tidak ditemukan!');
        return view('', compact('class_'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kelas $class_)
    {
        if($class_==null)
            return back()->with('status', 'Kelas tidak ditemukan!');
        $classData=$request->validated();
        $class_->update($classData);
        return redirect()->route('admin.kelas.index')
        ->with('status', 'Kelas berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelas $class_)
    {
        if($class_==null)
            return back()->with('status', 'Kelas tidak ditemukan!');
        $class_->delete();
        return redirect()->route('admin.kelas.index')
        ->with('status', 'Kelas berhasil dihapus!');
    }
}
