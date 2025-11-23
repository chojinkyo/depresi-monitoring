<?php

use Illuminate\Support\Facades\Route;

// Auth Routes
Route::view('/login', 'auth.login')->name('login');
Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');

// Dashboard Siswa Route
Route::view('/siswa', 'dashboard.siswa')->name('siswa.dashboard');

// Default redirect
Route::get('/', function () {
    return redirect()->route('login');
});
