<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PassportAuthController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function () {
    Route::get('/auth/user', [PassportAuthController::class, 'getUser']);
});


Route::prefix('/auth')->group(function() {
    Route::post('/send-login-link', [PassportAuthController::class, 'sendLoginLink']);
    Route::post('/verify-login', [PassportAuthController::class, 'verifyLogin']);
    Route::post('/send-otp', [PassportAuthController::class, 'sendOTP']);
    Route::post('/verify-otp', [PassportAuthController::class, 'verifyOTP']);
    Route::post('/login', [PassportAuthController::class, 'login']);
});