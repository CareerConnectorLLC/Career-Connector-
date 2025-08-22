<?php

namespace App\Http\Controllers\Frontend\Customer;

use Inertia\Inertia;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = $request->user()->clientBookings()
                        ->with(['service:id,name', 'provider:id,name'])
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        return Inertia::render('Frontend/client/Bookings', [
            'bookings' => $bookings,
        ]);
    }

    public function store(Request $request)
    {
        $startDate = now()->createFromFormat('Y-m-d h:i a', $request->date.' '.$request->time);
        $endDate = now()->parse($startDate)->addHour()->format('Y-m-d H:i:s');

        $token = \Illuminate\Support\Str::random(32);
        $meetingUrl = route('frontend.meeting.show', ['token' => $token]);

        $request->user()->clientBookings()->create([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'booking_number' => $this->generateUniqueBookingNumber(),
            'service_id' => $request->service_id,
            'provider_id' => $request->provider_id,
            'price' => $request->amount * 100,
            'meeting_url' => $meetingUrl,
        ]);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::find($id);
        
        if ($request->status === 'Completed') {
            $this->chargeTheRemainingAmount($booking);
        }

        $booking->status = $request->status;
        $booking->save();
    }

    private function generateUniqueBookingNumber()
    {
        do {
            $number = 'BOOK-' . strtoupper(uniqid());
        } while (Booking::where('booking_number', $number)->exists());

        return $number;
    }

    private function chargeTheRemainingAmount($booking)
    {
        $stripe = app('stripe');

        $client = auth()->user();

        $amount = $booking->price / 2;

        $paymentMethods = $stripe->customers->allPaymentMethods(
            $client->stripe_id, []
        );

        if ($paymentMethods->data[0]) {
            $stripe->paymentIntents->create([
                'amount' => $amount,
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
