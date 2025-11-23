<?php

namespace App\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Diary;
use App\Models\Kelas;
use App\Models\Presensi;
use App\Models\PresensiLibur;
use App\Models\RekapEmosi;
use App\Models\RiwayatKelas;
use App\Models\TahunAkademik;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator as ValidationValidator;

class PresensiController extends Controller
{
    protected $storage_path;
    public static function middleware()
    {
        return ['auth:sanctum', 'role:siswa'];
    }
    public function __construct()
    {
        $this->storage_path="/data/config/konfigurasi_jadwal_harian.json";
    }
    private function getSisJjg()
    {
        $id_sis=auth('sanctum')->user()->siswa?->id;
        $kls=RiwayatKelas::select('id')
        ->where('id_siswa', $id_sis)
        ->where('active', true)
        ->first()
        ?->kelas()
        ->pluck('kelas.jenjang');
        if($kls==null) return null;
        return $kls->jenjang;
    }
    private function cekLibur($tgl)
    {
        $jjg_kls=$this->getSisJjg(); // perbaiki pengecekan tingkat kelas
        $cur=Carbon::parse($tgl)->format('d-m');
        $lbr=PresensiLibur::select(['ket'])
            ->where('tgl_mulai', '<=', $cur)
            ->where('tgl_selesai', '>=', $cur)
            ->whereJsonContains('jenjang', $jjg_kls)
            ->first();
        if($lbr==null) return;
        throw ValidationException::withMessages(['tgl'=>'Libur, kegiatan kalender akademik']);
    }
    private function inTenggat($ls_jwl, $hr_id) : bool
    {
        $jadwal=
        array_filter($ls_jwl, function($jwl) use($hr_id) {
            return $jwl[0]==$hr_id;
        });
        if(count($jadwal)==0)
        {
            throw new \Exception('Jadwal untuk hari ini tidak ditemukan');
            return true;
        }
        $str=Carbon::createFromTimeString($jadwal[1]);
        $end=Carbon::createFromTimeString($jadwal[2]);
        return now()->between($str, $end, true);
    }
    private function cekPresensi($tgl)
    {
        $hr_id=(int)Carbon::parse($tgl)->format("w");

        $conf=Storage::json($this->storage_path);
        $hr_lbr=$conf['hari_libur'];
        $ls_jwl=$conf['jadwal'];
        
        $is_lbr=in_array($hr_id,$hr_lbr);
        if($is_lbr)
            throw ValidationException::withMessages(['tgl'=>'Hari libur']);

        $in_tgt=$this->inTenggat($ls_jwl,$hr_id);
        if(!$in_tgt)
            throw ValidationException::withMessages(['wkt'=>'Presensi sudah ditutup']);

    }
    public function create()
    {
        try
        {
            $tgl=now()->format('Y-m-d');
            $this->cekPresensi($tgl);
            $this->cekLibur($tgl);
            $response=
            [
                'msg'=>"Presensi untuk tanggal $tgl tersedia",
                'data'=>['is_open'=>true]
            ];
            return response()->json($response, 200);
        }
        catch(ValidationException $e)
        {
            $response=
            [
                'msg'=>"Presensi untuk tanggal $tgl tidak tersedia",
                'err'=>$e->getMessage(),
                'data'=>['is_open'=>true]
            ];
            return response()->json($response, 422);
        }
        catch(\Exception $e)
        {
            $response=
            [
                'msg'=>"Gagal mengecek jadwal",
                'err'=>$e->getMessage(),
                'data'=>['is_open'=>true]
            ];
            return response()->json($response, 500);
        }
    }
    private function getCurThak()
    {
        $thak=TahunAkademik::select('id', 'nama_tahun')->where('current', true)->first();
        if($thak==null) throw new \Exception('Tidak ada tahun akademik yang aktif', 404);
        return $thak;
    }
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'swafoto'=>'required|image|mime:jpg,png,jpeg|max:1024',
            'catatan'=>'required|max:255',
            'status'=>'required|in:H,I,S,A',
            'emoji'=>'required|integer|between:1,5',
            'ket'=>'required_if:status,I,S|max:255',
            'doc'=>'required_if:status,I,S|file|mime:pdf,jpg,png,jpeg|max:10240'
        ]);
        if($validator->fails())
        {
            return;
        }

        DB::beginTransaction();
        $data=$validator->validated();
        $siswa=auth('sanctum')->user()->siswa;
        try
        {
            $tgl=now()->format('Y-m-d');
            $this->cekPresensi($tgl);
            $this->cekLibur($tgl);

            $thak=$this->getCurThak();
            $id_sis=$siswa->id;
            $id_thak=$thak->id;
            $data_pres=
            [
                ...array_diff_key($data, array_flip(['swafoto', 'catatan', 'doc', 'emoji'])),
                'id_thak'=>$id_thak,
                'id_siswa'=>$id_sis,
            ];
            $presensi=Presensi::create($data_pres);

            $file=$request->file('swafoto');
            $dir_path='/data/images/diaries/'.$siswa->nisn.'/'.$thak->nama_tahun;
            $filename='pres_'.$siswa->nisn.'_'.now()->format('dmYHi').$file->getClientOriginalExtension();
            $strg_path=$dir_path.$filename;
            Storage::disk('public')->put($strg_path, file_get_contents($file));
            // Call the ML model for daily monitoring here.

            $data_diary=
            [
                ...array_diff_key($data, array_flip(['swafoto', 'doc', 'ket', 'status'])),
                'id_presensi'=>$presensi->id,
                'swafoto'=>$strg_path,
                'swafoto_pred'=>' ',
                'catatan_pred'=>' ',
                'catatan_ket'=>' ',
            ];
            Diary::create($data_diary);

            $lst_rkp=RekapEmosi::where('id_siswa',$id_sis)->sortBy('waktu')->first();
            $rkp_dte=$lst_rkp ? Carbon::parse($lst_rkp->waktu) : now()->subDays(14);
            if(now()->diffInDays($rkp_dte)>=14)
            {
                // Call the ML model for bi-weekly recap here
                $new_rkp_data=
                [
                    'tgl'=>now()->format('Y-m-d'),
                    'hasil'=>true,
                    'id_sis'=>$id_sis,
                    'rekap_emoji'=>5,
                ];
                RekapEmosi::create($new_rkp_data);
            }

            DB::commit();
            $code=200;
            $response=
            [
                'message'=>'Berhasil presensi',
                'data'=>[]
            ];
            return response()->json($response, $code);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            $code=200;
            $response=
            [
                'message'=>'Berhasil presensi',
                'data'=>[]
            ];
        }
    }
}
