<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use App\Rules\SatuTahunAjaranAktif;
use App\Rules\SingleActive;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
            return response()->json([
                'message'=>'Tahun ajaran tidak ditemukan'
            ], 404);
        }

        return response()->json([
            "message"=>"Tahun ajaran $row->tahun_mulai/$row->tahun_akhir",
            "data"=>$row
        ], 200);
    }
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'tahun_mulai'=>'required|date_format:Y|unique:tahun_ajarans',
            'is_aktif'=>['nullable','boolean',new SingleActive('tahun_ajarans','is_aktif')],
            'status'=>'nullable|in:aktif,nonaktif,ditutup,arsip',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'message'=>'Input invalid',
                'errors'=>$validator->errors()
            ], 422);
        }
        $data=$validator->validated();
        $tahun_akhir=(int)$data['tahun_mulai'] + 1;
        $data=[...$data,'tahun_akhir'=>Carbon::createFromDate($tahun_akhir,1,1)->format('Y')];
        if(isset($data['is_aktif']) && $data['is_aktif']) $data=[...$data,'status'=>'aktif'];

        $new_entri=TahunAjaran::create($data);
        return response()->json([
            'message'=>'Tahun ajaran baru berhasil dibuat',
            'data'=>$new_entri
        ], 200);
    }
    public function update(Request $request, $id)
    {
        $row=TahunAjaran::find($id);
        if($row==null)
        {
            return response()->json([
                'message'=>'Tahun ajaran tidak ditemukan'
            ], 404);
        }
        $validator=Validator::make($request->all(), [
            'tahun_mulai'=>'required|date_format:Y|unique:tahun_ajarans,tahun_mulai,'.$id,
            'is_aktif'=>['nullable','boolean',new SingleActive('tahun_ajarans','is_aktif',$id)],
            'status'=>'nullable|in:aktif,nonaktif,ditutup,arsip',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'message'=>'Input invalid',
                'errors'=>$validator->errors()
            ], 422);
        }
        
        $data=$validator->validated();
        $tahun_akhir=(int)$data['tahun_mulai'] + 1;
        $data=[...$data,'tahun_akhir'=>Carbon::createFromDate($tahun_akhir,1,1)->format('Y')];

        if(isset($data['is_aktif']) && $data['is_aktif']) $data=[...$data,'status'=>'aktif'];

        $row->update($data);
        return response()->json([
            'message'=>"Tahun ajaran $row->tahun_mulai/$row->tahun_akhir berhasil diupdate",
            'data'=>$row
        ], 200);
    }
    public function destroy($id)
    {
        $row=TahunAjaran::find($id);
        if($row==null)
        {
            return response()->json([
                'message'=>'Tahun ajaran tidak ditemukan'
            ], 404);
        }

        $row->delete();
        return response()->json([
            'message'=>'Tahun ajaran berhasil dihapus'
        ], 200);
    }
}
