<?php

use Illuminate\Support\Facades\Route;

// Auth Routes
Route::view('/login', 'auth.login')->name('login');
Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');

// Dashboard Siswa Routes
Route::view('/siswa', 'dashboard.siswa')->name('siswa.dashboard');
Route::view('/siswa/presensi', 'siswa.presensi')->name('siswa.presensi');
Route::view('/siswa/jadwal', 'siswa.jadwal')->name('siswa.jadwal');


// Default redirect
Route::get('/', function () {
    return redirect()->route('login');
});
