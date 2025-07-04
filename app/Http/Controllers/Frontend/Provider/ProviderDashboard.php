<?php

namespace App\Http\Controllers\Frontend\Provider;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProviderDashboard extends Controller
{
    public function __invoke(Request $request)
    {
        $bookings = $request->user()->providerBookings()->with('service:id,name', 'client:id,name')->get();
        
        return Inertia::render('Frontend/ProviderDashboard', [
            'bookings' => $bookings,
        ]);
    }
}
