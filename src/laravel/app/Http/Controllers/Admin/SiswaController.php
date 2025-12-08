<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiswaStoreRequest;
use App\Http\Requests\SiswaUpdateRequest;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\RiwayatKelas;
use App\Models\TahunAkademik;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    private function generateProfileName($file)
    {
        $now=now()->format('dmYHis');
        return uniqid().$now.$file->getClientOriginalExtension();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes=Kelas::all();
        $students=Siswa::all();
        $academicYears=TahunAkademik::where('status', true)->get()->sortBy(fn($item) => [1-$item->current, $item->nama_tahun]);
        return view('admin.siswa.index', compact('students', 'classes', 'academicYears'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes=Kelas::all();
        $academicYears=TahunAkademik::all();
        return view('', compact('classes', 'academicYears'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SiswaStoreRequest $request)
    {
        $validated=$request->validated();

        DB::beginTransaction();
        try 
        {
            // Set File Name
            
            $file=$request->file('avatar');
            $fileName=$file ? $this->generateProfileName($file) : "";

            // Create Data User
            $birthDate=Carbon::parse($validated['tanggal_lahir'])->format('dmY');
            $password='Nubi-'.$birthDate;
            $userData=[
                'email'=>$validated['email'],
                'username'=>$validated['nisn'],
                'password'=>$password,
                'avatar_url'=>$fileName,
                'role'=>'siswa'
            ];
            $user=User::create($userData);

            // Create Data Siswa
            $studentData=[
                ...array_diff_key($validated, array_flip(['email', 'avatar', 'id_kelas', 'status'])),
                'id_user'=>$user->id
            ];
            $student=Siswa::create($studentData);

            // Create Data Riwayat Kelas
            $classHistoryData=[
                'id_siswa'=>$student->id,
                'id_kelas'=>$validated['id_kelas'],
                'id_thak'=>$validated['id_thak_masuk'],
                'status'=>$validated['status'],
            ];
            $classHistory=RiwayatKelas::create($classHistoryData);

            // Store Avatar To Private Storage
            if($file)
            {
                $path="app/data/images/avatars/";
                $file->storeAs($path, $fileName, "private");
            }
            DB::commit();
            return redirect()->route('admin.siswa.index')
            ->with('status', 'Siswa berhasil dibuat!');
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return redirect()->route('admin.siswa.create')
            ->with('status', 'Siswa gagal dibuat!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $student)
    {
        if(!$student)
            return back()->with('status', 'Siswa tidak ditemukan!');
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $student)
    {
        if(!$student)
            return back()->with('status', 'Siswa tidak ditemukan!');

        $classes=Kelas::all();
        $academicYears=TahunAkademik::all();
        return view('admin.siswa.index', compact('student', 'classes', 'academicYears'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SiswaUpdateRequest $request, Siswa $student)
    {
        if(!$student)
            return back()->with('status', 'Siswa tidak ditemukan!');

        $validated=$request->validated();

        DB::beginTransaction();
        try 
        {
            // Initialize related data
            $relatedUser=$student->user;
            $activeClass=$student->getActiveClass();
            $enrollYear=$student->enrollYear;

            // Set File Name
            $file=$request->file('avatar');
            $fileName=$file ? $this->generateProfileName($file) : $relatedUser->avatar_url;

            // Create Data User
            $birthDate=Carbon::parse($validated['tanggal_lahir'])->format('dmY');
            $password='Nubi-'.$birthDate;
            $userData=[
                'email'=>$validated['email'],
                'username'=>$validated['nisn'],
                'password'=>$password,
                'avatar_url'=>$fileName,
                'role'=>'siswa'
            ];
            $relatedUser->update($userData);

            // Create Data Siswa
            $studentData=array_diff_key($validated, array_flip(['email', 'avatar', 'id_kelas', 'status']));
            $student->update($studentData);

            // Create Data Riwayat Kelas
            $isHistoryChanged=($validated['id_kelas']==$activeClass->id) || ($validated['id_thak']==$enrollYear->id);
            if($isHistoryChanged)
            {
                $firstClassHistory=$student->classHistory()->whereIn('status', ['NW', 'MM'])->first();
                $classHistoryData=[
                    'id_siswa'=>$student->id,
                    'id_kelas'=>$validated['id_kelas'],
                    'id_thak'=>$validated['id_thak_masuk'],
                    'status'=>$validated['status'],
                ];
                $classHistory=RiwayatKelas::create($classHistoryData);
                $firstClassHistory->update(['status'=>'CL', 'active'=>false]);
            }

            // Store Avatar To Private Storage
            if($file)
            {
                $path="app/data/images/avatars/";
                $file->storeAs($path, $fileName, "private");
                
                $oldProfilePath=$path.$relatedUser->avatar_url;
                $oldProfileExists=Storage::disk('private')->exists($oldProfilePath);
                if($oldProfileExists)
                    Storage::disk('private')->delete($oldProfilePath);
            }
            DB::commit();
            return redirect()->route('admin.siswa.index')
            ->with('status', 'Siswa berhasil dibuat!');
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return redirect()->route('admin.siswa.edit', ['id'=>$student->id])
            ->with('status', 'Siswa gagal dibuat!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $student)
    {
        if(!$student)
            return back()->with('status', 'Siswa tidak ditemukan!');

        $student->delete();
        return redirect()->route('admin.siswa.index')
        ->with('status', 'Siswa berhasil dihapus!');
    }
}
