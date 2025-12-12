<?php

namespace App\Http\Controllers;

use App\Models\PresensiLibur;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return 
        [
            new Middleware('role:siswa', only : ['siswaDashboard']),
            new Middleware('auth:sanctum', only : ['siswaDashboard']),
            new Middleware('auth', only : ['adminDashboard', 'guruDashboard']),
            new Middleware('role:admin,guru', only : ['adminDashboard', 'guruDashboard']),
        ];
    }
    
    private function getCalendarDays()
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
            if($day==0 ||count($calendar)==0)
            {
                $index=count($calendar);
                array_push($calendar, []);
            }
            array_push($calendar[$index], ['date'=>$date->day,'day'=>$day]);
        }
        // dd($calendar);
        return $calendar;
    
    }
    
    public function admin()
    {
        $month=now()->month;
        $calendars=$this->getCalendarDays();
        $vacations=PresensiLibur::select(['ket', 'tanggal_mulai', 'tanggal_selesai', 'bulan_mulai', 'bulan_selesai'])->where('bulan_mulai', $month)->orderBy('tanggal_mulai')->get()->toArray();
        
        return view('dashboard.admin', compact('calendars', 'vacations'));
    }
    public function siswaDashboard()
    {
        
    }
    public function guruDashboard()
    {

    }
}
