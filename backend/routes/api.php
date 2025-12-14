<?php

use App\Http\Controllers\ApproveServiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RequestController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

// Authenticated user routes
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::apiResource('approveServices', ApproveServiceController::class)->except('store', 'edit', 'create');

});

Route::get('/services', [RequestController::class, 'index']);
Route::post('/citizen-requests', [RequestController::class, 'store']);
 
Route::middleware(['auth:api', 'checkRole:supervisor'])->group(function () {
   
Route::apiResource('approveServices', ApproveServiceController::class)->except('store', 'edit', 'create');
  // OTP verification (for citizen)
Route::post('/otp/verify', [ApproveServiceController::class, 'verifyOtp']);

// Activate account (for citizen)
Route::post('/account/activate', [ApproveServiceController::class, 'activateAccount']);  
});
 