<?php

namespace App\Http\Controllers\Frontend\Customer;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ClientDashboard extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;

        $clientBookingsQuery = $request->user()->clientBookings(); // New variable

        $bookings = $clientBookingsQuery
            ->with('service:id,name', 'provider:id,name')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $conversations = Conversation::where('customer_id', $userId)
            ->orWhere('provider_id', $userId)
            ->with(['customer:id,name,profile_photo_path', 'provider:id,name,profile_photo_path', 'service:id,name', 'lastMessage'])
            ->withCount(['messages as unread_messages_count' => function ($query) use ($userId) {
                $query->where('receiver_id', $userId)->whereNull('read_at');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        $payments = $clientBookingsQuery // Use the new variable
            ->select(['id', 'price', 'booking_number', 'provider_id', 'service_id', 'status', 'updated_at'])
            ->with('service:id,name', 'provider:id,name')
            ->where('status', 'Completed')
            ->latest()
            ->take(3)
            ->get();

        $activeBookings = (clone $clientBookingsQuery)->where('status', 'Confirmed')->count();
        $cancelledBookings = (clone $clientBookingsQuery)->where('status', 'Cancelled')->count();
        $totalBookings = (clone $clientBookingsQuery)->count();

        return Inertia::render('Frontend/ClientDashboard', [
            'bookings' => $bookings,
            'conversations' => $conversations,
            'payments' => $payments,
            'activeBookings' => $activeBookings,
            'cancelledBookings' => $cancelledBookings,
            'totalBookings' => $totalBookings,
        ]);
    }
}