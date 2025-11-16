<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class SiswaController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return ['jwt:admin'];
    }
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
        $siswa=Siswa::find($id);
        if($siswa==null)
        {
            return response()->json([
                "message"=>"Siswa tidak ditemukan",
            ], 404);
        }
        return response()->json([
            "message"=>"Data siswa $id",
            "data"=>$siswa
        ]);
    }
    public function store(Request $request)
    {
        $current_year=now()->year;
        $current_date=now()->format('Y-m-d');
        $validator = Validator::make($request->all(), [
            "nama"=>"required|max:50",
            "alamat"=>"required|max:255",
            "gender"=>"required|in:1,0",
            "tanggal_lahir"=>"date|date_format:Y-m-d|before:$current_date",
            "tahun_masuk"=>"required|date_format:Y|before:$current_year",
            "avatar"=>"nullable|images|mimes:jpg,png,jpeg|max:500",
            "tingkat"=>"required|integer|max:3",
            "nisn"=>"required|unique:siswas|digits:10",
            "email"=>"required|email|unique:siswas",
            'no_telp'=>'required|unique:siswas|regex:/(08)[0-9]{9,11}/',
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
        

        DB::beginTransaction();
        try
        {
            $role=Role::select('id')->where('name','siswa')->first();
            if($role==null)
                throw new \Exception("Role 'siswa' tidak ditemukan");

            $data=$validator->validated();
            $user_data=[
                'email'=>$data['email'],
                'username'=>$data['nisn'],
                'password'=>Carbon::parse($data['tanggal_lahir'])->format('dmY'),
                'role_id'=>$role->id
            ];
            $user=User::create($user_data);
            $siswa_data=[...$data, 'id_user'=>$user->id];
            $siswa=Siswa::create($siswa_data);
            DB::commit();
            return response()->json([
                "message"=>"Siswa created successfully"
            ], 200);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return response()->json([
                "message"=>"Data siswa gagal ditambahkan, galat server",
                "error"=>$e->getMessage()
            ], 500);
        }
        
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
        $current_year=now()->year;
        $current_date=now()->format('Y-m-d');
        $validator = Validator::make($request->all(), [
            "nama"=>"required|max:50",
            "alamat"=>"required|max:255",
            "gender"=>"required|in:1,0",
            "tanggal_lahir"=>"date|date_format:Y-m-d|before:$current_date",
            "tahun_masuk"=>"required|date_format:Y|before:$current_year",
            "avatar"=>"nullable|images|mimes:jpg,png,jpeg|max:500",
            "tingkat"=>"required|integer|max:3",
            "nisn"=>"required|unique:siswas,nisn,$id,nisn|digits:10",
            "email"=>"required|email|unique:siswas,email,$id,nisn",
            "no_telp"=>"required|unique:siswas,no_telp,$id,nisn|regex:/(08)[0-9]{9,11}/",
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
        

        DB::beginTransaction();
        try
        {
            $role=Role::select('id')->where('name','siswa')->first();
            if($role==null)
                throw new \Exception("Role 'siswa' tidak ditemukan");

            $data=$validator->validated();
            
            $siswa->update($data);
            $user=$siswa->user;
            if($user->email!=$siswa->email || $user->username!=$siswa->nisn)
            {
                $new_user_data=[
                    'email'=>$siswa->email,
                    'username'=>$siswa->nisn
                ];
                $user->update($new_user_data);
            }
            DB::commit();
            return response()->json([
                "message"=>"Siswa $id updated successfully"
            ], 200);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return response()->json([
                "message"=>"Data siswa $id gagal diupdate, galat server",
                "err"=>$e->getMessage()
            ], 500);
        }
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
