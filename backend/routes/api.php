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
 
});

 
Route::apiResource('services',RequestController::class)->except('edit','update','create','destroy');
Route::post('/account/activate', [ApproveServiceController::class, 'activateAccount']);  
Route::post('/servicesstatus/{id}',[RequestController::class,'getStatus']);

Route::middleware(['auth:api', 'checkRole:supervisor'])->group(function () {
   
Route::apiResource('approveServices', ApproveServiceController::class)->except('store', 'edit', 'create');
  // OTP verification (for citizen)
});
 Route::post('/otp/verify', [ApproveServiceController::class, 'verifyOtp']);

// Activate account (for citizen)
