<?php

use App\Http\Controllers\App\Siswa\PresensiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\Admin\PresensiLiburController as HariLiburController;

Route::view('/', 'welcome');
Route::group(['middleware'=>['guest']], function() {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'postLogin'])->name('web.login.post');
});

Route::group(['middleware'=>['auth']], function() {
    Route::get('/files/{mime}/{type}/default', [ImageController::class, 'webDefault'])
    ->name('image.web.default');
    Route::get('/files/{mime}/{type}/{id_col}/{id}/{filepath}', [ImageController::class, 'webGet'])
    ->name('image.web.show');
    Route::post('/logout', [LoginController::class, 'postLogout'])->name('web.logout.post');
});
Route::group(['middleware'=>['auth', 'role:admin']], function() {
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'] );
    Route::view('/admin/kelas', 'admin.kelas.index')->name('admin.kelas.index');
    Route::view('/admin/siswa', 'admin.siswa.index')->name('admin.siswa.index');
    Route::view('/admin/tahun-akademik', 'admin.tahun_akademik.index')->name('admin.thak.index');
    Route::view('/admin/siswa/mental', 'admin.siswa.mental.index')->name('admin.siswa.mental.index');
    Route::view('/admin/siswa/kehadiran', 'admin.siswa.kehadiran.index')->name('admin.siswa.kehadiran.index');
    Route::get('/admin/hari-libur', [HariLiburController::class, 'index'])->name('admin.libur.index');
});
Route::group(['middleware'=>['auth', 'role:guru']], function() {
    Route::get('/guru/dashboard', [DashboardController::class, 'guruDashboard'] );
});
// Auth Routes
// Route::view('/login', 'auth.login')->name('login');
// Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');

// Dashboard Siswa Routes
Route::view('/siswa/login', 'auth.sanctum_login')->name('siswa.login');
Route::view('/siswa/presensi', 'siswa.presensi')->name('siswa.presensi');
Route::view('/siswa/jadwal', 'siswa.jadwal')->name('siswa.jadwal');
Route::view('/siswa', 'dashboard.siswa')->name('siswa.dashboard');



