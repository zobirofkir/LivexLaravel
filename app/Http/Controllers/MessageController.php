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
            'read' => $request->has('read') ? $request->read : false,
            'unread' => $request->has('unread') ? $request->unread : true,
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
          ->get();

        $unreadCount = $messages->where('unread', true)->count();

        $messages = $messages->map(function ($message) use ($user, $unreadCount) {
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
                'created_at' => $message->created_at,
                'read' => $message->read,
                'unread' => $message->unread,
                'unread_count' => $unreadCount,
                'user' => [
                    'id' => $otherParticipant->id,
                    'name' => $otherParticipant->name,
                    'email' => $otherParticipant->email,
                    // Add any other fields you need from the user model
                ],
            ];
        });

        return response()->json(['messages' => $messages->values()->all(), 'unread_count' => $unreadCount]);
    }

    /**
     * Mark a message as read or get its read/unread status.
     */
    public function markAsRead(Request $request, $messageId)
    {
        $message = Message::findOrFail($messageId);

        // Only receiver can mark as read or view status
        if ($message->receiver_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($request->isMethod('get')) {
            // Return read/unread status
            return response()->json([
                'id' => $message->id,
                'read' => $message->read,
                'unread' => $message->unread,
            ]);
        }

        // POST: mark as read
        $message->read = true;
        $message->unread = false;
        $message->save();

        return response()->json(['message' => 'Message marked as read', 'data' => $message]);
    }
}