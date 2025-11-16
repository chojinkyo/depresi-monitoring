<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Siswa;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas=Siswa::all();
        return response()->json([
            "message"=>"Semua siswa",
            "data"=>$siswas
        ], 200);
    }
    public function show($id)
    {
        $siswa = Siswa::find($id);
        if($siswa==null)
        {
            return response()->json([
                "message"=>"Siswa tidak ditemukan",
            ], 404);
        }
        return response()->json();
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "nama"=>"required|max:50",
            "alamat"=>"required|max:255",
            "gender"=>"required|in:1,0",
            "tanggal_lahir"=>"date",
            "tahun_masuk"=>"required",
            "avatar"=>"nullable",
            "tingkat"=>"required|number|max:3",
            "nisn"=>"required|unique:siswas",
            "email"=>"required|unique:siswas",
            "no_telp"=>"required|unique:siswas",
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
        
        Siswa::create($validator->validated());
        return response()->json([
            "message"=>"Siswa created successfully"
        ], 200);
    }
    public function update(Request $request, $id)
    {
        $siswa = Siswa::find($id);
        if($siswa==null)
        {
            return response()->json([
                "message"=>"Siswa tidak ditemukan",
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            "nama"=>"required",
            "nisn"=>"required|unique:siswas",
            "email"=>"required|unique:siswas",
            "no_telp"=>"required|unique:siswas",
            "alamat"=>"required",
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
        
        $siswa->update($validator->validated());
        return response()->json([
            "message"=>"Siswa updated successfully"
        ], 200);
    }
    public function destroy($id)
    {
        $siswa = Siswa::find($id);
        if($siswa==null)
        {
            return response()->json([
                "message"=>"Siswa tidak ditemukan",
            ], 404);
        }

        $siswa->delete();
        return response()->json([
            "message"=>"Siswa deleted successfully"
        ], 200);
    }
}
