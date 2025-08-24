<?php

use App\Http\Controllers\CoinController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->prefix("auth/chats")->group(function () {
    Route::apiResource('coins', CoinController::class);
});
