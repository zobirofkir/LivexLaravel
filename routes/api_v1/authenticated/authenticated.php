<?php

use App\Http\Controllers\api_v1\auth\AuthUserController;
use App\Http\Controllers\api_v1\live\VideoController;
use Illuminate\Support\Facades\Route;

/**
 * Authenticated Routes
 */
Route::middleware('auth:api')->prefix('auth/user')->group(function () {
    /**
     * Api Resource for Videos
     */
    Route::apiResource('/reels', VideoController::class)->except('update');

    /**
     * Update Video By Id
     */
    Route::post('reels/{id}', [VideoController::class, 'update']);

    /**
     * Update Current Authenticated Route
     */
    Route::post('/update', [AuthUserController::class, 'update'])->name('auth.user.update');

    /**
     * Get Current Authenticated User
     */
    Route::get('/current', [AuthUserController::class, 'show'])->name('auth.user.current');

    /**
     * Logout Current Authenticated User
     */
    Route::post('/logout', [AuthUserController::class, 'logout'])->name('auth.user.logout');
});
