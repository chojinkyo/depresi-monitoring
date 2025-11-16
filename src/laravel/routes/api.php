<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SiswaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/admin/register', [RegisterController::class, 'admin_register']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth');

Route::group(['middleware'=>['jwt:guest,auth']], function() {
    Route::resource('/admin/siswa', SiswaController::class);
});
