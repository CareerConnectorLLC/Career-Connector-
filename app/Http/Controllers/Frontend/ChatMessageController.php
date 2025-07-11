<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ChatMessageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'body' => 'required|string|max:1000',
        ]);

        $conversation = Conversation::findOrFail($request->conversation_id);

        // Ensure the authenticated user is part of the conversation before allowing them to post.
        if ($conversation->customer_id !== Auth::id() && $conversation->provider_id !== Auth::id()) {
             return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        $senderId = Auth::id();
        $receiverId = $conversation->customer_id === $senderId
            ? $conversation->provider_id
            : $conversation->customer_id;

        $message = Message::create([
            'conversation_id' => $request->conversation_id,
            'sender_id' => $senderId,
            'body' => $request->body,
            'receiver_id' => $receiverId,
        ]);

        // Load the 'sender' relationship so the sender's info is available on the frontend.
        $message->load('sender:id,name,profile_photo_path');

        // Here you would typically broadcast an event for real-time functionality.
        broadcast(new \App\Events\MessageSent($message))->toOthers();

        return response()->json(['message' => $message]);
    }
}
