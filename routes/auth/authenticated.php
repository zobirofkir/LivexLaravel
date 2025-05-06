<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthUserController;

Route::middleware('auth:api')->prefix('auth/user')->group(function () {
    Route::put('/update', [AuthUserController::class, 'update']);
    Route::get('/current', [AuthUserController::class, 'show']);
    Route::post('/logout', [AuthUserController::class, 'logout']);
});
