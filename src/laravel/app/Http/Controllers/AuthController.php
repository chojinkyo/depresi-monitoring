<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials=$request->only(['username', 'password']);
        $validator=Validator::make($credentials, [
            'username'=>'required',
            'password'=>'required'
        ]);
        if($validator->fails())
        {
            return response()->json([
                'message'=>'Invalid username/password',
            ], 422);
        }

        if(!$token=Auth::guard('api')->attempt($validator->validated()))
        {
            return response()->json([
                'message'=>'Unauthorized',
                'data'=>$validator->validated()
            ], 401);
        }

        return response()->json([
            'message'=>'Login success',
            'token'=>$token
        ], 200);
    }
}
