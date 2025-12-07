<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return 
        [
            new Middleware('guest', except : ['postLogout']),
            new Middleware('auth', only : ['postLogout']),
            
        ];
    }
    public function index()
    {
        return view('auth.web_login');
    }
    public function postLogin(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'username'=>'required',
            'password'=>'required'
        ]);
        if($validator->fails())
        {
            $response=
            [
                'msg'=>'Input salah.',
                'errs'=>$validator->errors()
            ];
            return back()->withErrors($response);
        }
        
        $credentials=$validator->validated();
        if(Auth::guard('web')->attempt($credentials, $request->remember))
        {
            $request->session()->regenerate();
            $role=auth('web')->user()->role;
            return redirect()->intended("/$role/dashboard");
        }
        $response=['credential'=>'username/password salah'];
        return back()->withErrors($response);
    }
    public function postLogout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
