<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\MessageRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Send a message from the authenticated user to another user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendMessage(MessageRequest $request)
    {
        $request->validated();

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
        ]);

        // Broadcast the message
        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['message' => 'Message sent successfully', 'data' => $message], 201);
    }

    /**
     * Get all messages for the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMessages()
    {
        $user = Auth::user();

        $sentMessages = $user->sentMessages()->with('receiver')->get();
        $receivedMessages = $user->receivedMessages()->with('sender')->get();

        return response()->json([
            'sent_messages' => $sentMessages,
            'received_messages' => $receivedMessages,
        ]);
    }
}