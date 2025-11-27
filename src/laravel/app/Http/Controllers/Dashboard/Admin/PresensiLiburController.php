<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Models\PresensiLibur;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class PresensiLiburController extends Controller
{
    // public static function middleware()
    // {
    //     return ['auth', 'role:admin'];
    // }
    private function getHrBlnIni()
    {
        $month=now()->month;
        $years=now()->year;

        $start=Carbon::create($years, $month, 1);
        $end=$start->copy()->endOfMonth();

        $index=0;
        $calendar=[];
        foreach(Carbon::parse($start)->toPeriod($end) as $date)
        {
            $day=$date->dayOfWeek;
            if($day==0||count($calendar)==0)
            {
                $index=count($calendar);
                array_push($calendar, []);
            }
            array_push($calendar[$index], [
                'date'=>$date->day,
                'day'=>$day
            ]);
        }
        return $calendar;
    }
    public function index()
    {
        $days=$this->getHrBlnIni();
        $lbr=PresensiLibur::all()->sortBy('tgl_mulai');
        return view('admin.hari_libur.index', compact('lbr', 'days'));
    }
    public function show(PresensiLibur $lbr)
    {
        if($lbr==null)
        {
            return;
        }

        return;
    }
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'ket'=>'required|max:255',
            'tgl_mulai'=>'required|date_format:d-m',
            'tgl_selesai'=>'nullable|date_format:d-m|after:tgl_mulai',
            'jenjang'=>'required|array',
            'jenjang.*'=>'required|between:1,3|distinct'
        ]);
        if($validator->fails())
        {
            return;
        }
        $user=auth('web')->user();
        $data=$validator->validated();
        $data_lbr=
        [
            ...$data,
            'id_author'=>$user->id,
            'tgl_selesai'=>$data['tgl_selesai'] ?? $data['tgl_mulai'],
        ];
        PresensiLibur::create($data_lbr);
        return;
    }
    public function update(Request $request, PresensiLibur $lbr)
    {
        if($lbr==null)
        {
            return;
        }
            
        $user=auth('web')->user();
        if($user->id != $lbr->id_author)
        {
            return;
        }

        $validator=Validator::make($request->all(), [
            'ket'=>'required|max:255',
            'tgl_mulai'=>'required|date_format:d-m',
            'tgl_selesai'=>'nullable|date_format:d-m|after:tgl_mulai',
            'jenjang'=>'required|array',
            'jenjang.*'=>'required|between:1,3|distinct'
        ]);
        if($validator->fails())
        {
            return;
        }
        
        $data=$validator->validated();
        $data_lbr=
        [
            ...$data,
            'tgl_selesai'=>$data['tgl_selesai'] ?? $data['tgl_mulai'],
        ];
        $lbr->update($data_lbr);
        return;
    }
    public function destroy(PresensiLibur $lbr)
    {
        if($lbr==null)
        {
            return;
        }
        $lbr->delete();
        return;
    }
}
