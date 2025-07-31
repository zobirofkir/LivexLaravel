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
    public function getMessages(Request $request, $userId)
    {
        $user = Auth::user();

        // Get sent messages to the specified user
        $sentMessages = $user->sentMessages()
            ->where('receiver_id', $userId)
            ->with('receiver')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'content' => $message->content,
                    'sender_id' => $message->sender_id,
                    'receiver_id' => $message->receiver_id,
                    'user' => $message->receiver, // The receiver is the other participant
                ];
            });

        // Get received messages from the specified user
        $receivedMessages = $user->receivedMessages()
            ->where('sender_id', $userId)
            ->with('sender')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'content' => $message->content,
                    'sender_id' => $message->sender_id,
                    'receiver_id' => $message->receiver_id,
                    'user' => $message->sender, // The sender is the other participant
                ];
            });

        // Combine both sent and received messages
        $allMessages = $sentMessages->merge($receivedMessages);

        // Sort messages by created_at timestamp
        $allMessages = $allMessages->sortBy('created_at');

        return response()->json([
            'messages' => $allMessages->values()->all(),
        ]);
    }
}