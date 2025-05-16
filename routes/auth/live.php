<?php

use App\Http\Controllers\LiveController;
use Illuminate\Support\Facades\Route;

/**
 * protected live routes
 */
Route::middleware('auth:api')->prefix('auth/user')->group(function() {
    /**
     * Go Live Route
     */
    Route::post('/start-live', [LiveController::class, 'goLive']);

    /**
     * Stop Live Route
     */
    Route::post('/stop-live', [LiveController::class, 'stopLive']);

    /**
     * Get Users Live
     */
    Route::get('/get-lives', [LiveController::class, 'getLiveUsers']);
});