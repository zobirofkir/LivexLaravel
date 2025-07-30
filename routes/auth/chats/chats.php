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
     * Route to get all messages for the authenticated user
     */
    Route::get('/messages', [MessageController::class, 'getMessages'])->name('messages.index');
});

/**
 * Test route to trigger a broadcast event for a message sent event
 */
Route::get('/test-broadcast', function () {
    $user = Auth::user(); 

    $message = Message::create([
        'sender_id' => $user->id, 
        'receiver_id' => 4, // Ensure this is a valid user ID
        'content' => 'This is a test message',
    ]);

    broadcast(new MessageSent($message))->toOthers();

    return response()->json(['status' => 'Broadcast event triggered']);
})->middleware('auth:api'); 
