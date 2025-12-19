<?php

use App\Http\Controllers\App\Siswa\PresensiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\Admin\PresensiLiburController as HariLiburController;
use App\Http\Controllers\Dashboard\Admin\SiswaController as AdminSiswaController;
use App\Http\Controllers\Dashboard\Admin\TahunAkademikController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Diperlukan untuk Auth::check()

// 1. Root/Default Page
// Mengarahkan pengguna berdasarkan status login. Jika sudah login, ke dashboard, jika belum, ke login.
Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        return redirect()->intended("/$role/dashboard");
    }
    return redirect()->route('login');
})->name('root');

// 2. Guest Routes (Hanya diakses jika BELUM login)
Route::group(['middleware'=>['guest']], function() {
    // Login
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'postLogin'])->name('web.login.post');

    // Forgot Password
    Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
});

// 3. Authenticated Routes (Hanya diakses jika SUDAH login)
Route::group(['middleware'=>['auth']], function() {
    // Logout (Menggunakan nama 'logout' yang lebih standar)
    Route::post('/logout', [LoginController::class, 'postLogout'])->name('logout');
});

// 4. Admin Routes (Akses: Auth + Role Admin)
Route::group(['middleware'=>['auth', 'role:admin']], function() {
    // Dashboard Admin (Ditambahkan nama route)
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');

    Route::view('/admin/kelas', 'admin.kelas.index')->name('admin.kelas.index');
    Route::view('/admin/tahun-akademik', 'admin.tahun_akademik.index')->name('admin.ta.index');

    // Resource Controller (Siswa dan Hari Libur)
    Route::resource('/admin/siswa', AdminSiswaController::class, ['as' => 'admin']);
    Route::resource('/admin/hari-libur', HariLiburController::class, ['as' => 'admin']);
});

// 5. Guru Routes (Akses: Auth + Role Guru)
Route::group(['middleware'=>['auth', 'role:guru']], function() {
    // Dashboard Guru (Ditambahkan nama route)
    Route::get('/guru/dashboard', [DashboardController::class, 'guruDashboard'])->name('guru.dashboard');
    
    // Fitur Guru Lainnya
    Route::get('/guru/laporan-mood', [\App\Http\Controllers\Dashboard\Guru\GuruSiswaController::class, 'moodIndex'])->name('guru.mood.index');
    Route::get('/guru/laporan-nilai', [\App\Http\Controllers\Dashboard\Guru\GuruSiswaController::class, 'nilaiIndex'])->name('guru.nilai.index');
});

// 6. Siswa Routes (Akses: Auth + Role Siswa)
Route::group(['middleware'=>['auth', 'role:siswa']], function() {
    // Dashboard Siswa
    Route::get('/siswa/dashboard', [DashboardController::class, 'siswaDashboard'])->name('siswa.dashboard');
    
    // Route Siswa Lainnya
    Route::view('/siswa/presensi', 'siswa.presensi')->name('siswa.presensi');
    Route::post('/siswa/presensi', [PresensiController::class, 'store'])->name('siswa.presensi.store');
    Route::view('/siswa/jadwal', 'siswa.jadwal')->name('siswa.jadwal');
    Route::view('/siswa/laporan-nilai', 'siswa.laporan-nilai')->name('siswa.laporan-nilai');
    Route::get('/siswa/statistik', [App\Http\Controllers\App\Siswa\StatistikController::class, 'index'])->name('siswa.statistik');
    Route::get('/siswa/diaryku', [App\Http\Controllers\App\Siswa\Dass21Controller::class, 'diarykuDashboard'])->name('siswa.diaryku');
    Route::view('/form-input-dass21', 'dass21.form-input')->name('dass21.form');
    Route::post('/siswa/dass21', [App\Http\Controllers\App\Siswa\Dass21Controller::class, 'store'])->name('dass21.store');
});