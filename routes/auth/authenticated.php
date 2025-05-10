<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthUserController;
use App\Http\Controllers\VideoController;

/**
 * Authenticated Routes
 */
Route::middleware('auth:api')->prefix('auth/user')->group(function () {

    /**
     * Api Resource for Videos
     */
    Route::apiResource('/reels', VideoController::class);

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
