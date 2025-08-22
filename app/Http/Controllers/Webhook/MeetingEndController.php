<?php

namespace App\Http\Controllers\Webhook;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MeetingEndController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $payload = $request->all();
        $apiKey = config('services.digitalsamba.developer_key');

        $response = \Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->get('https://api.digitalsamba.com/api/v1/rooms/'.$payload['roomId']);

        if (!$response->successful()) {
            return;
        }

        $roomData = $response->json();

        $booking = Booking::with(['client:id,name,stripe_id', 'provider:id,name'])->where('meeting_url', $roomData['friendly_url'])->first();

        if (!optional($booking->client)->stripe_id) {
            return;
        }

        $this->chargeTheRemainingAmount($booking);
    }

    private function chargeTheRemainingAmount(Booking $booking)
    {
        $stripe = app('stripe');
        $client = $booking->client;
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
                'description' => 'Meeting with provider: ' . $booking->provider->name,
                'off_session' => true,
                'confirm' => true,
            ]);

            $booking->status = 'Completed';
            $booking->save();
            \Log::info('Payment Successful');
        }
    }
}
