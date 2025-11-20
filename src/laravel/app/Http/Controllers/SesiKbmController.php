<?php

namespace App\Http\Controllers;

use App\Models\KalenderAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use \App\Models\SesiKbm;
use App\Models\TahunAjaran;
use \App\Models\User;
use App\Rules\KelasAdaDanTingkatSama;
use Carbon\Carbon;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class SesiKbmController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return ['auth:sanctum'];
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
        $tahun_ajaran=TahunAjaran::select('id')->where('is_aktif', true)->first();
        if($tahun_ajaran==null) 
        {
            $response=['message'=>'Tidak bisa membuat sesi kbm, tidak ada tahun ajaran yang aktif'];
            return response()->json($response, 401);
        }
        $validator = Validator::make($request->all(), [
            "waktu_mulai"=>"required|date_format:Y-m-d H:i",
            "waktu_akhir"=>"required|date_format:Y-m-d H:i|after:waktu_mulai",
            "batas_input_mulai"=>"nullable|date_format:Y-m-d H:i|after_or_equal:waktu_mulai",
            "batas_input_akhir"=>"required_with:batas_input_mulai|date_format:Y-m-d H:i|after:batas_input_mulai",
            "tingkat"=>"required|integer|between:1,3",
            "pemakaian"=>"required|in:all,some",
            "kelas"=>["required_with:tingkat","required_if:pemakaian,some","array","min:1",new KelasAdaDanTingkatSama($request->tingkat, count($request->kelas))],
        ]);
        if($validator->fails())
        {
            $response=
            [
                "message"=>"Invalid inputs",
                "errors"=>$validator->errors(),
            ];
            return response()->json($response,422);
        }
        $stored_data=$validator->validated();
        if(empty($stored_data['batas_input_mulai']))
        {
            $stored_data=
            [   
                ...$stored_data,
                'batas_input_mulai'=>$stored_data['waktu_mulai'],
                'batas_input_akhir'=>$stored_data['waktu_akhir']
            ];
        }
        $tanggal=Carbon::parse($stored_data['waktu_mulai'])->format('Y-m-d');
        $kalender=KalenderAkademik::where('id_tahun_ajaran',$tahun_ajaran->id)
                                    ->where('tanggal',$tanggal)
                                    ->first();
        if($kalender!=null)
        {
            if($stored_data['pemakaian']=='all')
            {
                $response=['message'=>''];
                return response()->json($response,422);
            }

            $kelas_libur=$kalender->kelas()->wherePivot('status', 'libur')->pluck('kelas.id')->toArray();
            $diff=array_merge(
                array_diff($kelas_libur, $stored_data['kelas']),
                array_diff($stored_data['kelas'], $kelas_libur)
            );
            if(count($diff)>0)
            {
                $diff=implode(", ", $diff);
                $response=["message"=>"Kelas dengan id $diff libur pada tanggal $tanggal"];
                return response()->json($response, 422);
            }
        }
        $stored_data['id_tahun_ajaran']=$tahun_ajaran->id;
        $response=["message"=>"Sesi KBM created successfully"];
        $kbm=SesiKbm::create($stored_data);
        if($stored_data['pemakaian']=='some')
            $kbm->kelas_libur()->sync($stored_data['kelas']);
        return response()->json($response, 200);
    }
    public function update(Request $request, $id)
    {
        $kbm = SesiKbm::find($id);
        if($kbm==null)
        {
            $response=["message"=>"Sesi KBM tidak ditemukan"];
            return response()->json($response, 404);
        }
        $tahun_ajaran=TahunAjaran::select('id')->where('is_aktif', true)->first();
        if($tahun_ajaran==null) 
        {
            $response=['message'=>'Tidak bisa mengupdate sesi kbm, tidak ada tahun ajaran yang aktif'];
            return response()->json($response, 401);
        }
        $validator = Validator::make($request->all(), [
            "waktu_mulai"=>"required|date_format:Y-m-d H:i",
            "waktu_akhir"=>"required|date_format:Y-m-d H:i|after:waktu_mulai",
            "batas_input_mulai"=>"nullable|date_format:Y-m-d H:i|after_or_equal:waktu_mulai",
            "batas_input_akhir"=>"required_with:batas_input_mulai|date_format:Y-m-d H:i|after:batas_input_mulai",
            "tingkat"=>"required|integer|between:1,3",
            "pemakaian"=>"required|in:all,some",
            "kelas"=>["required_with:tingkat","required_if:pemakaian,some","array","min:1",new KelasAdaDanTingkatSama($request->tingkat, count($request->kelas))],
        ]);

        if($validator->fails())
        {
            $response=
            [
                "message"=>"Invalid inputs",
                "errors"=>$validator->errors(),
            ];
            return response()->json($response,422);
        }
        $updated_data=$validator->validated();
        $tanggal=Carbon::parse($updated_data['waktu_mulai'])->format('Y-m-d');
        $kalender=KalenderAkademik::where('id_tahun_ajaran',$tahun_ajaran->id)
                                    ->where('tanggal',$tanggal)
                                    ->first();
        if($kalender!=null)
        {
            if($updated_data['pemakaian']=='all')
            {
                $response=['message'=>''];
                return response()->json($response,422);
            }

            $kelas_libur=$kalender->kelas()->wherePivot('status', 'libur')->pluck('kelas.id')->toArray();
            $diff=array_merge(
                array_diff($kelas_libur, $updated_data['kelas']),
                array_diff($updated_data['kelas'], $kelas_libur)
            );
            if(count($diff)>0)
            {
                $diff=implode(", ", $diff);
                $response=["message"=>"Kelas dengan id $diff libur pada tanggal $tanggal"];
                return response()->json($response, 422);
            }
        }
        
        $response=["message"=>"Sesi KBM updated successfully"];
        $kbm->update($updated_data);
        if($updated_data['pemakaian']=='some')
            $kbm->kelas_libur()->sync($updated_data['kelas']);
        return response()->json($response, 200);
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
            $config_path='/data/config/kalender_config.json';
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
            $data=[...$validator->validated()];
            $config_path='/data/config/kalender_config.json';
            $config_json=json_encode($data);

            Storage::put($config_path, $config_json);
            return response()->json(['message'=>'Konfigurasi berhasil diubah'], 200);
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
            'tingkat'=>'required|between:1,3'
        ]);
        if($validator->fails())
        {
            return response()->json([
                "message"=>"Invalid inputs",
                "errors"=>$validator->errors(),
            ], 422);
        }
        // add try catch
        $current_tahun_ajaran=TahunAjaran::where('is_aktif',true)->first();
        if($validator->fails())
        {
            $response=['message'=>'Tidak bisa membuat sesi KBM. Tidak ada tahun ajaran yang aktif'];
            return response()->json($response,422);
        }
        $tingkat=(int) $validator->validated()['tingkat'];
        $config_path='/data/config/kalender_config.json';
        $config=Storage::json($config_path);
        $hari_libur=$config['hari_libur'];
        $jadwal=$config['jadwal'];
        $mulai=Carbon::parse($current_tahun_ajaran->tanggal_mulai);
        $akhir=Carbon::parse($current_tahun_ajaran->tanggal_akhir);

        $data=[];
        $semua_kelas_libur=[];
        $days_diff=$mulai->diffInDays($akhir);

        for($i=0;$i<$days_diff;$i++)
        {
            $index=$i%7;
            
            if(in_array($index, $hari_libur))
            {
                $mulai->addDay();
                continue;
            }
            $tanggal=$mulai->format('Y-m-d');
            $waktu_mulai=$tanggal.' '.$jadwal[$index]['jam_mulai'];
            $waktu_akhir=$tanggal.' '.$jadwal[$index]['jam_akhir'];
            $kalender=KalenderAkademik::where('id_tahun_ajaran',$current_tahun_ajaran->id)
                                        ->where('tanggal',$tanggal)
                                        ->first();
            $new_data=
            [
                "id_tahun_ajaran"=>$current_tahun_ajaran->id,
                "waktu_mulai"=>$waktu_mulai,
                "waktu_akhir"=>$waktu_akhir,
                "batas_input_mulai"=>$waktu_mulai,
                "batas_input_akhir"=>$waktu_akhir,
                "pemakaian"=>"all",
                "tingkat"=>$tingkat,
            ];
            $kelas_libur=[];
            if($kalender!=null)
            {
                $kelas_libur=$kalender->kelas()->wherePivot('status','libur')->where('kelas.tingkat', $tingkat)->pluck('kelas.id')->toArray();
                if(!empty($kelas_libur))
                    $new_data['pemakaian']='some';
            }
            array_push($semua_kelas_libur, $kelas_libur);
            array_push($data, $new_data);
            $mulai->addDay();
        }
        DB::beginTransaction();
        try
        {
            $relationship_data=[];
            foreach($data as $key => $d)
            {
                $kbm=SesiKbm::create($d);
                foreach($semua_kelas_libur[$key] as $id)
                {
                    array_push($relationship_data, [
                        'id_kbm'=>$kbm->id,
                        'id_kelas'=>$id
                    ]);
                }
            }
            DB::table('kbm_kelas_liburs')->insert($relationship_data);
            DB::commit();
            return response()->json([
                'message'=>"Buat sesi otomatis untuk tingkat $tingkat berhasil dijalankan"
            ], 200);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'message'=>'Pembuatan sesi otomatis gagal'
            ], 500);
        }
        
    }
}
