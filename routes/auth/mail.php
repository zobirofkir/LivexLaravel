<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\EmailAuthController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/auth')->group(function() {
    Route::post('/send-otp', [EmailAuthController::class, 'sendOTP']);
    Route::post('/verify-otp', [EmailAuthController::class, 'verifyOTP']);
    Route::post('/login', [EmailAuthController::class, 'login']);
});