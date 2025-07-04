<?php

namespace App\Http\Controllers\Frontend\Customer;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientDashboard extends Controller
{
    public function __invoke(Request $request)
    {
        $bookings = $request->user()->clientBookings()->with('service:id,name', 'provider:id,name')->get();
        
        return Inertia::render('Frontend/ClientDashboard', [
            'bookings' => $bookings,
        ]);
    }
}
