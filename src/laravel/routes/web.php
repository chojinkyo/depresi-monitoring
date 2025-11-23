<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\Admin\PresensiLiburController as HariLiburController;
use App\Http\Controllers\Dashboard\Admin\SiswaController as AdminSiswaController;
use App\Http\Controllers\Dashboard\Admin\TahunAkademikController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'adminDashboard']);
Route::group(['middleware'=>['guest']], function() {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'postLogin'])->name('login.post');
    Route::post('/logout', [LoginController::class, 'postLogin'])->name('logout.post');

});


Route::group(['middleware'=>['auth', 'role:admin']], function() {
    Route::get('/admin/dashboard', );
    Route::resource('/admin/siswa', AdminSiswaController::class);
    Route::resource('/admin/hari-libur', HariLiburController::class);
    Route::resource('/admin/tahun-akademik', TahunAkademikController::class);
});
