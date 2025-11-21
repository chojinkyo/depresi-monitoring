<?php

namespace App\Http\Controllers;

use App\Models\KalenderAkademik;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KalenderAkademikController extends Controller
{
    public function index()
    {
        $tahun_ajaran=TahunAjaran::select('id')->where('is_aktif', true)->first();
        $id=$tahun_ajaran ? $tahun_ajaran->id : null;
        $kalender_akademik=KalenderAkademik::with('sesi_liburs')->where('id_tahun_ajaran', $id)->get();
        return response()->json([
            'message'=>'Kalender akademik semester ini',
            'data'=>$kalender_akademik
        ], 200);
    }
    public function store(Request $request)
    {
        $tahun_ajaran=TahunAjaran::select('id')->where('is_aktif', true)->first();
        $request->merge(['id_tahun_ajaran'=>$tahun_ajaran ? $tahun_ajaran->id : null]);
        $validator=Validator::make($request->all(), [
            'kegiatan'=>'required|max:255',
            'tanggal'=>'required|date_format:Y-m-d|after:today',
            'id_tahun_ajaran'=>'required',
            'kelas'=>'required|array|min:1',
            'kelas.*'=>
            [
                'required',
                Rule::exists('kelas', 'id')->where(function($query) {
                    $query->where('status', true);
                })
            ]
        ]);
        if($validator->fails())
        {
            return response()->json([
                'message'=>'Inputs failed'
            ], 422);
        }
        $data=$validator->validated();
        $kalender_data=array_diff_key($data, array_flip($data['kelas']));
        
        $kalender=KalenderAkademik::create($kalender_data);
        $kalender->sesi_liburs()->sync($data['kelas']);
        $response=['message'=>'Hari baru berhasil ditambahkan!'];
        return response()->json($response, 200);
    }
    public function update(Request $request, KalenderAkademik $kalender)
    {
        if($kalender==null)
        {
            $response=['message'=>'Hari tidak ditemukan'];
            return response()->json($response, 404);
        }
        $validator=Validator::make($request->all(), [
            'kegiatan'=>'required|max:255',
            'tanggal'=>'required|date_format:Y-m-d|after:today',
            'kelas'=>'required|array|min:1',
            'kelas.*'=>
            [
                'required',
                Rule::exists('kelas', 'id')->where(function($query) {
                    $query->where('status', true);
                })
            ]
        ]);
        if($validator->fails())
        {
            $response=['message'=>'Inputs failed'];
            return response()->json($response, 422);
        }
        $data=$validator->validated();
        $kalender_data=array_diff_key($data, array_flip($data['kelas']));
        
        $kalender->update($kalender_data);
        $kalender->sesi_liburs()->sync($data['kelas']);
        $response=['message'=>'Hari berhasil diupdate!'];
        return response()->json($response, 200);
    }
    public function destroy(KalenderAkademik $kalender)
    {
        if($kalender==null)
        {
            $response=['message'=>'Hari tidak ditemukan'];
            return response()->json($response, 404);
        }
        $kalender->sesi_liburs()->delete();
        $kalender->delete();
        $response=['message'=>'Hari berhasil dihapus!'];
        return response()->json($response,404);
    }

}
