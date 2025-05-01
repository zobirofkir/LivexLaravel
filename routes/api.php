<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PassportAuthController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/send-login-link', [PassportAuthController::class, 'sendLoginLink']);
Route::post('/auth/verify-login', [PassportAuthController::class, 'verifyLogin']);
Route::post('/auth/send-otp', [PassportAuthController::class, 'sendOTP']);
Route::post('/auth/verify-otp', [PassportAuthController::class, 'verifyOTP']);
Route::post('/auth/login', [PassportAuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/auth/user', [PassportAuthController::class, 'getUser']);
});
