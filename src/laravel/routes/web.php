<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\Admin\PresensiLiburController as HariLiburController;
use App\Http\Controllers\Dashboard\Admin\SiswaController as AdminSiswaController;
use App\Http\Controllers\Dashboard\Admin\TahunAkademikController;
use App\Http\Controllers\DashboardController;
use App\Models\Kelas;
use Illuminate\Support\Facades\Route;

// 1. Root/Default Page
Route::view('/', 'welcome');

// 2. Guest Routes (Login, Register, Forgot Password)
Route::group(['middleware'=>['guest']], function() {
    // Login
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'postLogin'])->name('web.login.post');
    // Forgot Password (Diambil dari HEAD)
    Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
});

// 3. Authenticated Routes (Logout)
Route::group(['middleware'=>['auth']], function() {
    Route::post('/logout', [LoginController::class, 'postLogout'])->name('web.logout.post');
});

// 4. Admin Routes
Route::group(['middleware'=>['auth', 'role:admin']], function() {
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'] );
    Route::view('/admin/kelas', 'admin.kelas.index');
    Route::view('/admin/tahun-akademik', 'admin.tahun_akademik.index');
    Route::resource('/admin/siswa', AdminSiswaController::class);
    Route::resource('/admin/hari-libur', HariLiburController::class);
});

// 5. Guru Routes
Route::group(['middleware'=>['auth', 'role:guru']], function() {
    Route::get('/guru/dashboard', [DashboardController::class, 'guruDashboard'] );
});

// 6. Siswa Routes (Diambil dari HEAD, ditambahkan middleware)
Route::group(['middleware'=>['auth', 'role:siswa']], function() {
    Route::view('/siswa/dashboard', 'dashboard.siswa')->name('siswa.dashboard');
    Route::view('/siswa/presensi', 'siswa.presensi')->name('siswa.presensi');
    Route::view('/siswa/jadwal', 'siswa.jadwal')->name('siswa.jadwal');
});