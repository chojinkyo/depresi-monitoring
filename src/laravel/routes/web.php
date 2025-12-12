<?php

use App\Http\Controllers\Admin\DiaryController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\PresensiController as AdminPresensiController;
use App\Http\Controllers\Admin\PresensiLiburDataController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\TahunAkademikController;
use App\Http\Controllers\App\Siswa\PresensiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\Admin\PresensiLiburController as HariLiburController;
use App\Models\TahunAkademik;

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
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    Route::view('/siswa/mental', 'admin.siswa.mental.index')->name('admin.siswa.mental.index');
    Route::resource('/siswa/kehadiran', AdminPresensiController::class)->except(['destroy', 'create', 'store', 'edit', 'update', 'show']);
    Route::resource('/siswa/diary', DiaryController::class)->except(['destroy', 'create', 'store', 'edit', 'update']);
    Route::resource('/siswa', SiswaController::class);
    Route::resource('/kelas', KelasController::class)->except(['edit'])->parameter("kelas", "kelas");
    Route::resource('/tahun-akademik', TahunAkademikController::class);
    Route::get('/siswa/kehadiran/{student}/{year}', [AdminPresensiController::class, 'show'])->name('siswa.kehadiran.show');

    
    // Route::get('/dashboard', [DashboardController::class, 'adminDashboard'] );
    // Route::view('/kelas', 'admin.kelas.index')->name('admin.kelas.index');
    // Route::view('/tahun-akademik', 'admin.tahun_akademik.index')->name('admin.thak.index');
    
    // Route::get('/hari-libur', [HariLiburController::class, 'index'])->name('admin.libur.index');
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



