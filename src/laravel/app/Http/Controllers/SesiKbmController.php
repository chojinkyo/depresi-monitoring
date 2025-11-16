<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use \App\Models\SesiKbm;
use \App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SesiKbmController extends Controller
{
    // public function index()
    // {
    //     $claim;
    //     $id=$claim->get('id');
    //     $role=$claim->get('role');
    //     $school_id=$claim->get('school_id');
    //     $user=User::find('id');

    //     if($user->role!='admin'||$user->school_id!=$school_id)
    //     {
    //         return response()->json([
    //             'message'=>'unauthorized access'
    //         ], 402);
    //     }
        
    //     $records=SesiKbm::where('id_sekolah', $school_id)->groupBy('tingkat')->get();
    //     return response()->json([
    //         "message"=>"Daftar Semua Sesi KBM",
    //         "data"=>$records
    //     ], 200);
    // }
    // public function show($id)
    // {
    //     $entri= SesiKbm::with('log_harians')->find($id);
    //     if($entri=null)
    //     {
    //         return response()->json([
    //             "message"=>"Sesi KBM tidak ditemukan",
    //         ], 404);
    //     }
    //     return response()->json([
    //         "message"=>"Data sesi KBM dengan id $id",
    //         "data"=>$entri
    //     ], 200);
    // }
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         "tanggal"=>"required",
    //         "waktu_mulai"=>"required",
    //         "waktu_akhir"=>"required",
    //         "tingkat"=>"required|min:1|max:3"
    //     ]);

    //     if($validator->fails())
    //     {
    //         return response()
    //         ->json(
    //             [
    //                 "message"=>"Invalid inputs",
    //                 "errors"=>$validator->errors(),
    //             ]
    //             , 422
    //         );
    //     }
    //     $claim=null;
    //     $school_id=$claim->get('school_id');
    //     $stored_data = [...$validator->validated(), "school_id"=>$school_id];
    //     SesiKbm::create($stored_data);
    //     return response()->json([
    //         "message"=>"Sesi KBM created successfully"
    //     ], 200);
    // }
    // public function update(Request $request, $id)
    // {
    //     $sesi_kbm = SesiKbm::find($id);
    //     if($sesi_kbm==null)
    //     {
    //         return response()->json([
    //             "message"=>"Sesi KBM tidak ditemukan",
    //         ], 404);
    //     }
    //     $validator = Validator::make($request->all(), [
    //         "tanggal"=>"required",
    //         "waktu_mulai"=>"required",
    //         "waktu_akhir"=>"required",
    //         "tingkat"=>"required|min:1|max:3"
    //     ]);

    //     if($validator->fails())
    //     {
    //         return response()
    //         ->json(
    //             [
    //                 "message"=>"Invalid inputs",
    //                 "errors"=>$validator->errors(),
    //             ]
    //             , 422
    //         );
    //     }
    //     $claim=null;
    //     $school_id=$claim->get('school_id');
    //     $updated_data = [...$validator->validated(), "school_id"=>$school_id];
        
    //     $sesi_kbm->update($updated_data);
    //     return response()->json([
    //         "message"=>"Sesi KBM updated successfully"
    //     ], 200);
    // }
    // public function destroy($id)
    // {
    //     $sesi_kbm = SesiKbm::find($id);
    //     if($sesi_kbm==null)
    //     {
    //         return response()->json([
    //             "message"=>"Sesi KBM tidak ditemukan",
    //         ], 404);
    //     }

    //     $sesi_kbm->delete();
    //     return response()->json([
    //         "message"=>"Sesi KBM deleted successfully"
    //     ], 200);
    // }
    // public function bulk_auto_store(Request $request)
    // {
    //     $validator=Validator::make($request->all(), [
    //         'tingkat'=>'required'
    //     ]);
    //     if($validator->fails())
    //     {
    //         return response()
    //         ->json(
    //             [
    //                 "message"=>"Invalid inputs",
    //                 "errors"=>$validator->errors(),
    //             ]
    //             , 422
    //         );
    //     }
    //     // add try catch
    //     $claim=null;
    //     $school_id=$claim->get('school_id');
    //     $tingkat=$validator->validated()['tingkat'];
    //     $config_path=storage_path('app/data/config.json');
    //     $config=json_decode(file_get_contents($config_path),true);
    //     $mulai=Carbon::parse($config['tanggal_mulai']);
    //     $akhir=Carbon::parse($config['tanggal_akhir']);
    //     $jadwal=$config['jadwal'];
    //     $hari_libur=$config['weekdays'] ?? [];
    //     $data=[];

    //     for($date=$mulai->copy();$date->lte($akhir);$date->addDay())
    //     {
    //         $day=$date->format('l');
    //         if(in_array($day, $hari_libur)) continue;
    //         $jam_mulai=$jadwal[$day]['jam_mulai'];
    //         $jam_akhir=$jadwal[$day]['jam_akhir'];
    //         array_push($data, [
    //             "tanggal"=>$date,
    //             "jam_mulai"=>$jam_mulai,
    //             "jam_akhir"=>$jam_akhir,
    //             "tingkat"=>$tingkat,
    //             "id_sekolah"=>$school_id
    //         ]);
    //     }
        
    //     DB::table('sesi_kbms')->insert($data);
    //     return response()->json([
    //         'message'=>"Buat sesi otomatis untuk tingkat $tingkat berhasil dijalankan"
    //     ], 200);
        
    // }
}
