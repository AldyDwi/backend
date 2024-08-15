<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BajuController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\JenisBajuController;

// Register
Route::post('register',[AuthController::class, 'register']);

// Login
Route::post('login',[AuthController::class, 'login']);

Route::group([
    "middleware" => ["auth:sanctum", "role:admin"]
], function(){
    // Profile
    Route::get('admin/profile',[AuthController::class, 'profile']);

    // Logout
    Route::get('admin/logout',[AuthController::class, 'logout']);

    // CRUD Jenis
    Route::apiResource('jenis', JenisBajuController::class);

    // CRUD Baju
    Route::apiResource('baju', BajuController::class);
});

Route::group([
    "middleware" => ["auth:sanctum", "role:customer"]
], function(){
    // Profile
    Route::get('customer/profile',[AuthController::class, 'profile']);

    // Logout
    Route::get('customer/logout',[AuthController::class, 'logout']);
});













// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
