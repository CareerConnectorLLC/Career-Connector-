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

        $providerBookingsQuery = $request->user()->providerBookings(); // New variable

        $bookings = $providerBookingsQuery
            ->with('service:id,name', 'client:id,name')
            ->latest()
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

        $payments = $providerBookingsQuery // Use the new variable
            ->select(['id', 'price', 'booking_number', 'client_id', 'service_id', 'status', 'updated_at'])
            ->with('service:id,name', 'client:id,name')
            ->where('status', 'Completed')
            ->latest()
            ->take(3)
            ->get();

        $totalEarnings = (clone $providerBookingsQuery)->whereIn('status', ['Confirmed', 'Completed'])->sum('price');
        $pendingPayments = (clone $providerBookingsQuery)->where('status', 'Confirmed')->sum('price');
        $completedPayments = (clone $providerBookingsQuery)->where('status', 'Completed')->sum('price');

        return Inertia::render('Frontend/ProviderDashboard', [
            'bookings' => $bookings,
            'conversations' => $conversations,
            'payments' => $payments,
            'totalEarnings' => $totalEarnings,
            'pendingPayments' => $pendingPayments,
            'completedPayments' => $completedPayments,
            'pageTitle' => env('APP_NAME') . ' | Provider Dashboard'
        ]);
    }
}