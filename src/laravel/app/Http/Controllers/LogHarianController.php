<?php

namespace App\Http\Controllers;

use App\Models\LogHarian;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class LogHarianController extends Controller
{
    //
    public function index()
    {

    }
    public function update(Request $request, $id)
    {
        $claim="";
        $log=LogHarian::find($id);
        if($log==null)
        {
            return response()->json([
                'message'=>'Log not found'
            ], 404);
        }
        $validator=Validator::make($request->all(), [
            'keterangan'=>'required|in:hadir,izin,sakit,tanpa keterangan',
            'label'=>'required',
            'catatan'=>'nullable',
            'swafoto'=>'required'
        ]);
        // save to storage
        if($validator->fails())
        {
            return response()->json([
                'message'=>'Input invalid',
                'error'=>$validator->errors()
            ], 422);
        }
        $current_time=Carbon::now()->format('l');
        $jam_akhir=$log->jam_akhir;
        // time check here
        // check data depresi here
        
        $log->update($validator->validated());
    }
    
}
