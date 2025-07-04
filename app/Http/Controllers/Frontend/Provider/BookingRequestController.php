<?php

namespace App\Http\Controllers\Frontend\Provider;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\CommissionSetting;
use App\Http\Controllers\Controller;

class BookingRequestController extends Controller
{
    public function index(Request $request)
    {
        $bookings = $request->user()->providerBookings()->with([
            'service:id,name',
            'client:id,name'
        ])->get();

        return Inertia::render('Frontend/provider/BookingRequest', [
            'bookings' => $bookings
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => ['required']
        ]);

        $booking = $request->user()->providerBookings()->where('id', $id)->first();

        if ($request->status === 'Confirmed') {
            $this->makeStripeChargeForCustomer($booking);
        }

        $booking->status = $request->status;
        $booking->save();
    }

    private function makeStripeChargeForCustomer($booking)
    {
        $stripe = app('stripe');        
        $client = User::find($booking->client_id);
        
        $commission = CommissionSetting::first();
        $upfrontFee = ($booking->price / 2) + ($commission->booking_charge * 100);

        $paymentMethods = $stripe->customers->allPaymentMethods(
            $client->stripe_id, []
        );

        if ($paymentMethods->data[0]) {
            $stripe->paymentIntents->create([
                'amount' => $upfrontFee,
                'currency' => 'USD',
                'customer' => $client->stripe_id,
                'payment_method' => $paymentMethods->data[0]->id,
                'description' => '',
                'off_session' => true,
                'confirm' => true,
            ]);
        }
    }
}
