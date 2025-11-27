<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Sanctum\LoginController;
use App\Http\Controllers\App\Siswa\PresensiController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth');


Route::group(['middleware'=>['guest:sanctum']], function() {
    Route::post('/login', [LoginController::class, 'postLogin'])->name('sanctum.login.post');
    Route::post('/logout', [LoginController::class, 'postLogin'])->name('sanctum.logout.post');
});

Route::group(['middleware'=>['auth:sanctum','role:siswa']], function() {
    Route::get('/siswa/presensi', [PresensiController::class, 'create']);
    Route::post('/siswa/presensi', [PresensiController::class, 'store']);
});

