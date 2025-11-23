<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class JadwalHarianController extends Controller
{
    //
    private $storage_path;
    public function __construct()
    {
        $filename='konfigurasi_jadwal_harian.json';
        $this->storage_path="/data/config/$filename";
    }
    public function index()
    {
        try
        {
            $config=Storage::json($this->storage_path, 'public');
            return;
        }
        catch(\Exception $e)
        {

        }
    }
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'hari_libur'=>'required|array',
            'hari_libur.*'=>'required|integer|between:0,6|distinct',
            'jadwal'=>'required|array|max:7',
            'jadwal.*'=>'required|array|between:3,3',
            'jadwal.*.0'=>'required|integer|between:0,6|distinct',
            'jadwal.*.1'=>'required|date_format:H:i',
            'jadwal.*.2'=>'required|date_format:H:i|after:jadwal.*.1'
        ]);
        if($validator->fails())
        {
            return;
        }

        try
        {
            $data=$validator->validated();
            $ctn_json=json_encode($data);
            Storage::put($this->storage_path, $ctn_json);
            return;
        }
        catch(\Exception $e)
        {
            return;
        }

    }
}
