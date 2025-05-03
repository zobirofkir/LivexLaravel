<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PhoneAuthController;

Route::post('/send-otp', [PhoneAuthController::class, 'sendOTP']);
Route::post('/verify-otp', [PhoneAuthController::class, 'verifyOTP']);
Route::post('/login', [PhoneAuthController::class, 'login']);
