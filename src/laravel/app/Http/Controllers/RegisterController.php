<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function admin_register(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'nip'=>'required|digits:18|unique:admins|unique:users,username',
            'nama'=>'required|max:50',
            'password'=>'required|between:6,35|regex:/[A-Z]/|regex:/[0-9]/|regex:/[\W_]/',
            'email'=>'required|unique:admins|email',
            'no_telp'=>'required|unique:admins|regex:/(08)[0-9]{9,11}/',
            'confirm_password'=>'required|same:password',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'message'=>'Input failed',
                'errors'=>$validator->errors()
            ], 401);
        }
        $role=Role::select('id')->where('name','admin')->first();
        if($role==null) return;


        $data=$validator->validated();
        $admin_data=array_diff_key($data, array_flip(['password']));
        $data=array_diff_key($data, array_flip(['confirm_password']));
        $user_data=[
            'email'=>$data['email'],
            'username'=>$data['nip'],
            'password'=>$data['password'],
            'role_id'=>$role->id
        ];

        DB::beginTransaction();
        try
        {
            $user=User::create($user_data);
            $admin_data=[...$admin_data,'id_user'=>$user->id];
            Admin::create($admin_data);

            DB::commit();
            return response()->json([
                'message'=>'Data admin berhasil dibuat',
            ], 200);
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'message'=>'Gagal membuat data admin',
                'err'=>$e->getMessage(),
                'data'=>$admin_data
            ], 500);
        }
    }
}
