<?php

use App\Http\Controllers\LiveController;
use App\Http\Controllers\LiveKitTokenController;
use App\Http\Controllers\LiveStreamController;
use Illuminate\Support\Facades\Route;

/**
 * protected live routes
 */
Route::middleware('auth:api')->prefix('auth/user')->group(function() {
    /**
     * Api Resource for Live Streams
     */
    Route::apiResource('/lives', LiveStreamController::class);

    /**
     * LiveKit token endpoint
     */
    Route::get('/livekit-token', [LiveKitTokenController::class, 'getToken']);
});