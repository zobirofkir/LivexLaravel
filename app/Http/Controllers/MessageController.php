<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Send a message from the authenticated user to another user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
        ]);

        return response()->json(['message' => 'Message sent successfully', 'data' => $message], 201);
    }

    /**
     * Get all messages for the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMessages()
    {
        $user = auth()->user();

        $sentMessages = $user->sentMessages()->with('receiver')->get();
        $receivedMessages = $user->receivedMessages()->with('sender')->get();

        return response()->json([
            'sent_messages' => $sentMessages,
            'received_messages' => $receivedMessages,
        ]);
    }
}