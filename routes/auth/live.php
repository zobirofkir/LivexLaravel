<?php

use App\Http\Controllers\LiveController;
use Illuminate\Support\Facades\Route;


/**
 * Go Live Route
 */
Route::post('/go-live', [LiveController::class, 'goLive']);

/**
 * Stop Live Route
 */
Route::post('/stop-live', [LiveController::class, 'stopLive']);

/**
 * Get Lives Route
 */
Route::get('/live-users', [LiveController::class, 'getLiveUsers']);