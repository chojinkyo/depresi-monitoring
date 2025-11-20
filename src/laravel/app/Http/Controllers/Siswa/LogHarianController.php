<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\LogHarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LogHarianController extends Controller
{
    public function logging(Request $request,LogHarian $log_harian)
    {
        // pengecekan kepemilikan log
        $log=$log_harian;
        $validator=Validator::make($request->all(), [
            'label'=>'required|in:senang,marah,sedih,takut,jijik',
            'catatan'=>'nullable|max:255',
            'keterangan'=>'required|in:alpa,hadir,izin,sakit',
            'lampiran'=>'required_if:keterangan,izin,sakit',
            'swafoto'=>'required|images|mimes:png,jpg,jpeg|max:1024',
            'id_sesi_kbm'=>'required|exists:sesi_kbms'
        ]);
        if($validator->fails())
        {
            return response()->json([
                'message'=>'Input invalid',
                'errors'=>$validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try
        {
            $data=array_diff($validator->validated(), array_flip(['swafoto']));

            $log->update($validator->validated());
            DB::commit();
            return response()->json([
                'message'=>'Absensi berhasil dilakukan'
            ], 200);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'message'=>'Absensi gagal. Coba lagi nanti'
            ], 500);
        }

    }
}
