<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Diary;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\TahunAkademik;

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
        return view('auth.sanctum_login');
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
            $user=auth('web')->user();
            $role=$user->role;

            $this->setQuetionaryStatus($user);
            return redirect("/$role/dashboard");
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

    private function setQuetionaryStatus($user)
    {
        if($user->role==="siswa")
        {

            try
            {
                $siswa=$user->siswa;
                $yearId=TahunAkademik::orderByRaw('(3 - current - status) ASC')->orderBy('nama_tahun', 'desc')->first()?->id;

                if($yearId==null)
                {
                    dd('Tahun tidak ditemukan');
                }

                $mentalData=Diary::orderBy('waktu', 'desc')->whereHas('attendance', function($query) use($siswa, $yearId) {
                    return $query->where('id_thak', $yearId)->where('id_siswa', $siswa->id);
                })->get();
                $depressionRate=$mentalData->reduce(function($acc, $row) {
                    $swafoto_pred=strtolower($row->swafoto_pred);
                    $catatan_pred=strtolower($row->catatan_pred);
                    $bool=($catatan_pred==='terindikasi depresi' && !in_array($swafoto_pred, ['happy', 'surprise']));
                    return $acc + (int) $bool;
                }, 0);
                
                $siswa->need_survey=$depressionRate >= 70;
                $siswa->save();
            }
            catch(\Exception $e)
            {
                
                dd($e->getMessage());
            }
        }
    }
}
