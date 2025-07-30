<?php

use App\Events\MessageSent;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;
use App\Models\Message;

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

/**
 * Test route to trigger a broadcast event for a message sent event
 */

Route::get('/test-broadcast', function () {
    $message = Message::create([
        'sender_id' => 1, 
        'receiver_id' => 2, 
        'content' => 'This is a test message',
    ]);

    broadcast(new MessageSent($message))->toOthers();

    return response()->json(['status' => 'Broadcast event triggered']);
});
