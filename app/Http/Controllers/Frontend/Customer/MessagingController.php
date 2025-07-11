<?php

namespace App\Http\Controllers\Frontend\Customer;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class MessagingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;

        $conversationsQuery = Conversation::where('customer_id', $userId)
            ->orWhere('provider_id', $userId);

        // Conditionally load the other participant in the conversation
        if ($user->hasRole('USER')) {
            $conversationsQuery->with('provider:id,name,profile_photo_path');
        } elseif ($user->hasRole('SERVICE-PROVIDER')) {
            $conversationsQuery->with('customer:id,name,profile_photo_path');
        }

        $conversations = $conversationsQuery
            ->with('service:id,name')
            ->withCount(['messages as unread_messages_count' => function ($query) use ($userId) {
                $query->where('receiver_id', $userId)->whereNull('read_at');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Frontend/client/Messaging', [
            'conversations' => $conversations,
        ]);
    }

    public function show(Request $request, $id)
    {
        $conversation = Conversation::find($id);

        $messages = $conversation->messages()->with([
            'sender:id,name,profile_photo_path',
            'receiver:id,name,profile_photo_path',
        ])->get();

        return response()->json([
            'messages' => $messages,
        ]);
    }

    public function store(Request $request)
    {
        $conversation = Conversation::firstOrCreate([
            'customer_id' => auth()->id(),
            'provider_id' => $request->provider_id,
            'service_id' => $request->service_id
        ]);

        return redirect()->route('frontend.messaging.index', [
            'conversation' => $conversation->id,
            'service' => $conversation->service_id,
        ]);
    }

    public function markAsRead(Conversation $conversation)
    {
        // Authorization check to ensure the user is part of the conversation.
        if ($conversation->customer_id !== auth()->id() && $conversation->provider_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $conversation->messages()
            ->where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }
}
