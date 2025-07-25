<?php

namespace App\Http\Controllers\Frontend\Provider;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProviderDashboard extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;

        $bookings = $request->user()->providerBookings()->with('service:id,name', 'client:id,name')->get();

        $conversations = Conversation::where('customer_id', $userId)
            ->orWhere('provider_id', $userId)
            ->with(['customer:id,name,profile_photo_path', 'provider:id,name,profile_photo_path', 'service:id,name', 'lastMessage'])
            ->withCount(['messages as unread_messages_count' => function ($query) use ($userId) {
                $query->where('receiver_id', $userId)->whereNull('read_at');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Frontend/ProviderDashboard', [
            'bookings' => $bookings,
            'conversations' => $conversations,
        ]);
    }
}