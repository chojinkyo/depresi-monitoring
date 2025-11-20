<?php

namespace App\Http\Controllers;

use App\Models\Angkatan;
use App\Models\TahunAjaran;
use App\Rules\SatuTahunAjaranAktif;
use App\Rules\SingleActive;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $records=TahunAjaran::all();
        return response()->json([
            'message'=>'Riwayat tahun ajaran',
            'data'=>$records
        ]);
    }
    public function show($id)
    {
        $row=TahunAjaran::find($id);
        if($row==null)
        {
            $response=['message'=>'Tahun ajaran tidak ditemukan'];
            return response()->json($response, 404);
        }

        $response=
        [
            "message"=>"Tahun ajaran $row->tahun_mulai/$row->tahun_akhir",
            "data"=>$row
        ];
        return response()->json($response, 200);
    }
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'tahun_mulai'=>'required|date_format:Y|unique:tahun_ajarans|unique:angkatans,tahun',
            'tanggal_mulai'=>'required|date_format:Y-m-d',
            'tanggal_akhir'=>'required|date_format:Y-m-d|after:tanggal_mulai',
            'is_aktif'=>['nullable','boolean',new SingleActive('tahun_ajarans','is_aktif')],
            'status'=>'nullable|in:aktif,nonaktif,ditutup,arsip',
        ]);
        if($validator->fails())
        {
            $response=
            [
                'message'=>'Input invalid',
                'errors'=>$validator->errors()
            ];
            return response()->json($response, 422);
        }
        $data=$validator->validated();
        $tahun_akhir=(int)$data['tahun_mulai'] + 1;
        $data=[...$data,'tahun_akhir'=>"$tahun_akhir"];

        if(isset($data['is_aktif']) && $data['is_aktif']) 
            $data=[...$data,'status'=>'aktif'];
        
        DB::beginTransaction();
        try
        {
            $tahun_ajaran=TahunAjaran::create($data);
            $response=
            [
                'message'=>'Tahun ajaran baru berhasil dibuat',
                'data'=>$tahun_ajaran
            ];
            DB::commit();
            return response()->json($response, 200);
        }
        catch(\Exception $e)
        {
            $response=['message'=>'Gagal membuat tahun ajaran baru'];
            DB::rollBack();
            return response()->json($response);
        }
    }
    public function update(Request $request, $id)
    {
        $tahun_ajaran=TahunAjaran::find($id);
        if($tahun_ajaran==null)
        {
            $response=['message'=>'Tahun ajaran tidak ditemukan'];
            return response()->json($response, 404);
        }
        $validator=Validator::make($request->all(), [
            'tahun_mulai'=>'required|date_format:Y|unique:tahun_ajarans,tahun_mulai,'.$id.'unique:angkatans,tahun,id_tahun_mulai,'.$id,
            'tanggal_mulai'=>'required|date_format:Y-m-d',
            'tanggal_akhir'=>'required|date_format:Y-m-d|after:tanggal_mulai',
            'is_aktif'=>['nullable','boolean',new SingleActive('tahun_ajarans','is_aktif',$id)],
            'status'=>'nullable|in:aktif,nonaktif,ditutup,arsip',
        ]);
        if($validator->fails())
        {
            $response=
            [
                'message'=>'Input invalid',
                'errors'=>$validator->errors()
            ];
            return response()->json($response, 422);
        }
        
        $data=$validator->validated();
        $tahun_akhir=(int)$data['tahun_mulai'] + 1;
        $data=[...$data,'tahun_akhir'=>"$tahun_akhir"];

        if(isset($data['is_aktif']) && $data['is_aktif']) 
            $data=[...$data,'status'=>'aktif'];

        $tahun_ajaran->update($data);
        $response=
        [
            'message'=>"Tahun ajaran $tahun_ajaran->tahun_mulai/$tahun_ajaran->tahun_akhir berhasil diupdate",
            'data'=>$tahun_ajaran
        ];
        return response()->json($response, 200);
    }
    public function destroy($id)
    {
        $row=TahunAjaran::find($id);
        if($row==null)
        {
            $response=['message'=>'Tahun ajaran tidak ditemukan'];
            return response()->json($response, 404);
        }

        $row->delete();
        $response=['message'=>'Tahun ajaran berhasil dihapus'];
        return response()->json($response, 200);
    }
}
