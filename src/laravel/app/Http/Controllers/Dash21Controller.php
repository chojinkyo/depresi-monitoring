<?php

namespace App\Http\Controllers;

use App\Models\Dash21;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Dash21Controller extends Controller
{
    //
    private function generateGeneralRule()
    {
        try
        {
            $fields=Storage::json('/data/config/kuesioner_fields.json');
            if(empty($fields['fields']))
                throw new \Exception('kuesioner_fields incorrectly formatted');
            $rules=[];
            foreach($fields['fields'] as $f => $conf)
                $rules=[...$rules, $f=>$conf['rules']];
            return $rules;
        }
        catch(\Exception $e)
        {
            return [];
        }
    }
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),$this->generateGeneralRule());
        if($validator->fails())
        {
            return response()->json([
                'message'=>'Inputs invalid'
            ],422);
        }
        $result=false;
        // call an api here
        // get siswa id here
        $nisn='129013';

        try
        {
            $storage_path='/data/kuesioners/dash21s';
            $filename=now()->format('dmY').'_'.$nisn.'.json';
            $content=json_encode($validator->validated());
            $filepath=$storage_path.$filename;

            Storage::put($content, $filepath);

            $stored_data=[
                'kuesioner_url'=>$filepath,
                'id_siswa'=>$nisn,
                'result'=>$result
            ];
            Dash21::create($stored_data);
            return response()->json([
                'message'=>'Kuisioner berhasil disimpan', 
                'data'=>['depressed'=>$result]
            ], 200);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'message'=>'Gagal mengunggah kuesioner. Galat server. Coba lagi'
            ], 500);
        }
    }
}
