<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;

Route::middleware('auth:api')->prefix("auth/chats")->group(function () {
    
    /**
     * Route to send a message
     */
    Route::post('/messages/send', [MessageController::class, 'sendMessage'])->name('messages.send');

    /**
     * Route to get all messages for the authenticated user
     */
    Route::get('/messages', [MessageController::class, 'getMessages'])->name('messages.index');
});
