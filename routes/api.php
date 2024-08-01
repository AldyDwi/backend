<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\BajuController;
use App\Http\Controllers\Api\JenisBajuController;

// Register
Route::post('register',[ApiController::class, 'register']);

// Login
Route::post('login',[ApiController::class, 'login']);

Route::group([
    "middleware" => ["auth:sanctum"]
], function(){
    // Profile
    Route::get('profile',[ApiController::class, 'profile']);

    // Logout
    Route::get('logout',[ApiController::class, 'logout']);

    // CRUD Jenis
    Route::apiResource('jenis', JenisBajuController::class);

    // CRUD Baju
    Route::apiResource('baju', BajuController::class);
});











// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
