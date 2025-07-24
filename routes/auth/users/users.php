<?php

use App\Http\Controllers\api_v1\users\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->prefix('auth')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
});
