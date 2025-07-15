<?php

use App\Http\Controllers\Api\OfferController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    
    /**
     * User's own offers
     */
    Route::get('/my-offers', [OfferController::class, 'myOffers']);
    
    /**
     * CRUD operations for offers
     */
    Route::apiResource('offers', OfferController::class);
});

/**
 * Public routes
 * 
 * list all offers
 */
Route::get('/offers', [OfferController::class, 'index']);

/**
 * List just one offer
 */
Route::get('/offers/{offer}', [OfferController::class, 'show']);