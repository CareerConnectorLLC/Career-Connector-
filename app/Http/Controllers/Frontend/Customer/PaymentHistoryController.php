<?php

namespace App\Http\Controllers\Frontend\Customer;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PaymentHistoryController extends Controller
{
    public function index()
    {
        $bookings = Auth::user()->clientBookings()
            ->select(['id', 'service_id', 'provider_id', 'booking_number', 'start_date', 'end_date', 'status', 'price'])
            ->with(['service:id,name', 'provider:id,name'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return Inertia::render('Frontend/client/PaymentHistory', [
            'bookings' => $bookings,
        ]);
    }
}
