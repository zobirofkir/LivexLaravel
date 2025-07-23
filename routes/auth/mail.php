<?php

use App\Http\Controllers\api_v1\auth\EmailAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/auth/email')->group(function() {
    Route::post('/send-otp', [EmailAuthController::class, 'sendOTP']);
    Route::post('/verify-otp', [EmailAuthController::class, 'verifyOTP']);
    Route::post('/login', [EmailAuthController::class, 'login']);
});