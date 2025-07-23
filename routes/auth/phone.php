<?php

use App\Http\Controllers\api_v1\auth\PhoneAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth/phone')->group(function () {
    Route::post('/send-otp', [PhoneAuthController::class, 'sendOTP']);
    Route::post('/verify-otp', [PhoneAuthController::class, 'verifyOTP']);
    Route::post('/login', [PhoneAuthController::class, 'login']);
});