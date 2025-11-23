<?php

use Illuminate\Support\Facades\Route;

// Auth Routes
Route::view('/login', 'auth.login')->name('login');
Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');

// Dashboard Siswa Routes
Route::view('/siswa', 'dashboard.siswa')->name('siswa.dashboard');  // ← Ubah ini
Route::view('/siswa/presensi', 'siswa.presensi')->name('siswa.presensi');

// Default redirect
Route::get('/', function () {
    return redirect()->route('login');
});
