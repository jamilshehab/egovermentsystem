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

// Supervisor routes
Route::middleware(['auth:api', 'checkRole:supervisor'])->group(function () {
    Route::apiResource(
        'approveServices',
        ApproveServiceController::class
    )->except('store', 'edit', 'create');
});

// Citizen public routes
Route::apiResource(
    'services',
    RequestController::class
)->except('edit','update','create','destroy');

// Citizen status check
Route::get(
    '/citizen-requests/{id}/status',
    [RequestController::class, 'getStatus']
);

// OTP + activation
Route::post('/otp/verify', [ApproveServiceController::class, 'verifyOtp']);
Route::post('/account/activate', [ApproveServiceController::class, 'activateAccount']);
