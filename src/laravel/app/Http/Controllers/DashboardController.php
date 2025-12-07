<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return 
        [
            new Middleware('auth', only : ['adminDashboard', 'guruDashboard']),
            new Middleware('auth:sanctum', only : ['siswaDashboard']),
            new Middleware('role:admin,guru', only : ['adminDashboard', 'guruDashboard']),
            new Middleware('role:siswa', only : ['siswaDashboard'])
        ];
    }
    public function adminDashboard()
    {
        return view('dashboard.admin');
    }
    public function siswaDashboard()
    {
        
    }
    public function guruDashboard()
    {

    }
}
