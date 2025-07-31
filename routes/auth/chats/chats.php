<?php

use App\Events\MessageSent;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

Route::middleware('auth:api')->prefix("auth/chats")->group(function () {
    
    /**
     * Route to send a message
     */
    Route::post('/messages/send', [MessageController::class, 'sendMessage'])->name('messages.send');

    /**
     * Route to get messages for a specific user
     */
    Route::get('/messages/{userId}', [MessageController::class, 'getMessages'])->name('messages.index');

    /**
     * Route to mark a message as read (POST) or get read status (GET)
     */
    Route::post('/messages/{messageId}/read', [MessageController::class, 'markAsRead'])->name('messages.markAsRead');
    Route::get('/messages/{messageId}/read', [MessageController::class, 'markAsRead'])->name('messages.readStatus');
});
