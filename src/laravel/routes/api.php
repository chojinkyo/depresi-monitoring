<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\KalenderAkademikController;
use App\Http\Controllers\SesiKbmController;
use App\Http\Controllers\TahunAjaranController;
use App\Models\SesiKbm;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/admin/register', [RegisterController::class, 'admin_register']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth');

Route::group(['middleware'=>['auth:sanctum']], function() {
    Route::post('/admin/sesi-kbm/bulk-create', [SesiKbmController::class, 'bulk_auto_store']);
    Route::post('/admin/sesi-kbm/config', [SesiKbmController::class, 'config_update']);
    Route::get('/admin/sesi-kbm/config', [SesiKbmController::class, 'get_config']);
    Route::resource('/admin/siswa', SiswaController::class);
    Route::resource('/admin/sesi-kbm', SesiKbmController::class);
    Route::resource('/admin/tahun-ajaran', TahunAjaranController::class);
    Route::resource('/admin/kalender-akademik', KalenderAkademikController::class);
});
Route::group(['middleware'=>['auth:sanctum']], function() {
    Route::post('/siswa/absen', []);
    Route::put("/siswa/absen/{id}", []);
});
