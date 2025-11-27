<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\Admin\PresensiLiburController as HariLiburController;
use App\Http\Controllers\Dashboard\Admin\SiswaController as AdminSiswaController;
use App\Http\Controllers\Dashboard\Admin\TahunAkademikController;
use App\Http\Controllers\DashboardController;
use App\Models\Kelas;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');
Route::group(['middleware'=>['guest']], function() {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'postLogin'])->name('web.login.post');
});

Route::group(['middleware'=>['auth']], function() {
    Route::post('/logout', [LoginController::class, 'postLogout'])->name('web.logout.post');
});
Route::group(['middleware'=>['auth', 'role:admin']], function() {
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'] );
    Route::view('/admin/kelas', 'admin.kelas.index');
    Route::view('/admin/tahun-akademik', 'admin.tahun_akademik.index');
    Route::resource('/admin/siswa', AdminSiswaController::class);
    Route::resource('/admin/hari-libur', HariLiburController::class);
});
Route::group(['middleware'=>['auth', 'role:guru']], function() {
    Route::get('/guru/dashboard', [DashboardController::class, 'guruDashboard'] );
});
