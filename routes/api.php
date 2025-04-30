<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\FirebaseAuthController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/send-login-link', [FirebaseAuthController::class, 'sendLoginLink']);
Route::post('/auth/verify-login', [FirebaseAuthController::class, 'verifyLogin']);
Route::post('/auth/send-otp', [FirebaseAuthController::class, 'sendOTP']);
Route::post('/auth/verify-otp', [FirebaseAuthController::class, 'verifyOTP']);
Route::post('/auth/login', [FirebaseAuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/auth/user', [FirebaseAuthController::class, 'getUser']);
});
