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




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth');

Route::group(['middleware'=>['guest']], function() {

});
Route::group(['middleware'=>['auth']], function() {

});

Route::group(['middleware'=>['guest:sanctum']], function() {

});

Route::group(['middleware'=>['auth:sanctum']], function() {
    
});
Route::group(['middleware'=>['auth:sanctum']], function() {
    
});
