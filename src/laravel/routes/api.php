<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Sanctum\LoginController;



// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth');


Route::group(['middleware'=>['guest:sanctum']], function() {
    Route::post('/login', [LoginController::class, 'postLogin'])->name('sanctum.login.post');
    Route::post('/logout', [LoginController::class, 'postLogin'])->name('sanctum.logout.post');
});

Route::group(['middleware'=>['auth:sanctum']], function() {
    
});
Route::group(['middleware'=>['auth:sanctum']], function() {
    
});
