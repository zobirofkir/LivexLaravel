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

        // Get all messages between the authenticated user and the specified user
        $messages = Message::where(function ($query) use ($user, $userId) {
            $query->where('sender_id', $user->id)
                  ->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($user, $userId) {
            $query->where('sender_id', $userId)
                  ->where('receiver_id', $user->id);
        })->with(['sender', 'receiver'])
          ->get()
          ->map(function ($message) use ($user) {
              // Always return the other participant's info
              if ($message->sender_id === $user->id) {
                  $otherParticipant = $message->receiver;
              } else {
                  $otherParticipant = $message->sender;
              }

              return [
                  'id' => $message->id,
                  'content' => $message->content,
                  'sender_id' => $message->sender_id,
                  'receiver_id' => $message->receiver_id,
                  'user' => [
                      'id' => $otherParticipant->id,
                      'name' => $otherParticipant->name,
                      'email' => $otherParticipant->email,
                      // Add any other fields you need from the user model
                  ],
              ];
          });

        return response()->json([
            'messages' => $messages->values()->all(),
        ]);
    }
}