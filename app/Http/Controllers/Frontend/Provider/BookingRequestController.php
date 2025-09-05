<?php

namespace App\Http\Controllers\Frontend\Provider;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\CommissionSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Mail\ProviderMeetingNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BookingRequestController extends Controller
{
    public function index(Request $request)
    {
        $bookings = $request->user()->providerBookings()->with([
            'service:id,name',
            'client:id,name'
        ])->latest()->paginate(15);

        return Inertia::render('Frontend/provider/BookingRequest', [
            'bookings' => $bookings,
            'pageTitle' => env('APP_NAME') . ' | Provider Booking Requests'
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
            $this->generateTheMeeting($booking);
        }

        $booking->status = $request->status;
        $booking->save();

        if ($request->status === 'Confirmed') {
            session()->flash('success', 'Booking confirmed successfully.');
        } else {
            session()->flash('success', 'Booking cancelled successfully.');
        }
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

    private function generateTheMeeting($booking)
    {
        $apiKey = config('services.digitalsamba.developer_key');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.digitalsamba.com/api/v1/rooms', [
            'topic' => 'Booking with ' . $booking->provider->name,
            'privacy' => 'private',
            "roles" => ["moderator", "speaker"],
            "default_role" => "moderator"
        ]);

        if ($response->successful()) {
            $meetingData = $response->json();
            $friendlyUrlCode = $meetingData['friendly_url'];

            $booking->meeting_url = $friendlyUrlCode;
            $booking->provider_join_token = Str::random(40);
            $booking->provider_join_token_expires_at = now()->addHours(1);
            $booking->save();

            // Send email to provider
            Mail::to($booking->provider->email)->send(new ProviderMeetingNotification($booking, $booking->provider->name, $booking->provider_join_token));
        } else {
            // Handle the error, e.g., log it or show an error message
            // For now, let's just dump the error response
            dd($response->json());
        }
    }
}
