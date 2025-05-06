<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthUserController;

Route::middleware('auth:api')->prefix('auth/user')->group(function () {
    Route::put('/update', [AuthUserController::class, 'update'])->name('auth.user.update');
    Route::get('/current', [AuthUserController::class, 'show'])->name('auth.user.current');
    Route::post('/logout', [AuthUserController::class, 'logout'])->name('auth.user.logout');
});
