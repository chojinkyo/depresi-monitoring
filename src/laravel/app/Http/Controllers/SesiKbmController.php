<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use \App\Models\SesiKbm;
use \App\Models\User;
use Carbon\Carbon;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class SesiKbmController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return ['jwt:admin'];
    }
    public function index()
    {
        $records=SesiKbm::all()->groupBy('tingkat');
        return response()->json([
            "message"=>"Daftar Semua Sesi KBM",
            "data"=>$records
        ], 200);
    }
    public function show($id)
    {
        $entri=SesiKbm::with('log_harians')->find($id);
        if($entri==null)
        {
            return response()->json([
                "message"=>"Sesi KBM tidak ditemukan",
            ], 404);
        }
        return response()->json([
            "message"=>"Data sesi KBM dengan id $id",
            "data"=>$entri
        ], 200);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "tanggal"=>"required|date|date_format:Y-m-d",
            "jam_mulai"=>"required|date_format:H:i",
            "jam_akhir"=>"required|date_format:H:i|after:jam_mulai",
            "tingkat"=>[
                "required",
                "integer",
                "between:1,3",
            ]
        ]);

        if($validator->fails())
        {
            return response()
            ->json(
                [
                    "message"=>"Invalid inputs",
                    "errors"=>$validator->errors(),
                ]
                , 422
            );
        }
        
        $stored_data = $validator->validated();
        SesiKbm::create($stored_data);
        return response()->json([
            "message"=>"Sesi KBM created successfully"
        ], 200);
    }
    public function update(Request $request, $id)
    {
        $sesi_kbm = SesiKbm::find($id);
        if($sesi_kbm==null)
        {
            return response()->json([
                "message"=>"Sesi KBM tidak ditemukan",
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            "tanggal"=>"required|date|date_format:Y-m-d",
            "jam_mulai"=>"required|date_format:H:i",
            "jam_akhir"=>"required|date_format:H:i|after:jam_mulai",
            "tingkat"=>"required|integer|between:1,3"
        ]);

        if($validator->fails())
        {
            return response()
            ->json(
                [
                    "message"=>"Invalid inputs",
                    "errors"=>$validator->errors(),
                ]
                , 422
            );
        }
        $updated_data = $validator->validated();
        
        $sesi_kbm->update($updated_data);
        return response()->json([
            "message"=>"Sesi KBM updated successfully"
        ], 200);
    }
    public function destroy($id)
    {
        $sesi_kbm = SesiKbm::find($id);
        if($sesi_kbm==null)
        {
            return response()->json([
                "message"=>"Sesi KBM tidak ditemukan",
            ], 404);
        }

        $sesi_kbm->delete();
        return response()->json([
            "message"=>"Sesi KBM deleted successfully"
        ], 200);
    }
    public function get_config()
    {
        try
        {
            $config_path='app/data/config.json';
            $config=json_decode(Storage::get($config_path),true);
            return response()->json([
                'message'=>'Konfigurasi pembuatan sesi kbm massal',
                'data'=>$config
            ], 200);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'message'=>'Konfigurasi tidak ditemukan',
                'errors'=>$e->getMessage()
            ], 500);
        }
    }
    public function config_update(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'tanggal_mulai'=>'required|date|date_format:Y-m-d',
            'tanggal_akhir'=>'required|date|date_format:Y-m-d|after:tanggal_mulai',
            'hari_libur'=>'required|array',
            'hari_libur.*'=>'integer|min:1|max:7|distinct',
            'jadwal'=>'required|array|between:7,7',
            'jadwal.*.jam_mulai'=>'required|date_format:H:i',
            'jadwal.*.jam_akhir'=>'required|date_format:H:i|after:jam_mulai'
        ]);
        if($validator->fails())
        {
            return response()->json([
                'message'=>'Input failed',
                'errors'=>$validator->errors()
            ], 422);
        }
        try
        {
            $config_path='app/data/config.json';
            $config_json=json_encode($validator->validated());

            Storage::put($config_path, $config_json);
            return response()->json(['message'=>storage_path()], 200);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'message'=>'Gagal mengubah konfigurasi',
                'errors'=>$e->getMessage()
            ], 500);
        }
    }
    public function bulk_auto_store(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'tingkat'=>'required'
        ]);
        if($validator->fails())
        {
            return response()
            ->json(
                [
                    "message"=>"Invalid inputs",
                    "errors"=>$validator->errors(),
                ]
                , 422
            );
        }
        // add try catch
        $tingkat=$validator->validated()['tingkat'];
        $config_path='app/data/config.json';
        $config=json_decode(Storage::get($config_path),true);
        $mulai=Carbon::parse($config['tanggal_mulai']);
        $akhir=Carbon::parse($config['tanggal_akhir']);
        $jadwal=$config['jadwal'];
        $hari_libur=$config['hari_libur'];
        $data=[];
        $days_diff=$mulai->diffInDays($akhir);

        for($i=0;$i<$days_diff;$i++)
        {
            $index=$i%7;
            
            if(in_array($index, $hari_libur))
            {
                $mulai->addDay();
                continue;
            }
            $jam_mulai=$jadwal[$index]['jam_mulai'];
            $jam_akhir=$jadwal[$index]['jam_akhir'];
            array_push($data, [
                "tanggal"=>$mulai->format('Y-m-d'),
                "jam_mulai"=>$jam_mulai,
                "jam_akhir"=>$jam_akhir,
                "tingkat"=>$tingkat,
            ]);
            $mulai->addDay();
        }
        
        SesiKbm::insert($data);
        return response()->json([
            'message'=>"Buat sesi otomatis untuk tingkat $tingkat berhasil dijalankan"
        ], 200);
        
    }
}
