<?php

namespace App\Http\Controllers\Frontend\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        sleep(2);
        
        return response(null, 200);
        
        $stripeCustomerId = $this->getOrCreateStripeCustomerId();
        
        $paymentMethods = app('stripe')->customers->allPaymentMethods(
            $stripeCustomerId, []
        );

        $paymentIntent = app('stripe')->paymentIntents->create([
            'amount' => $request->amount * 100,
            'currency' => 'USD',
            'customer' => $stripeCustomerId,
            'payment_method' => $paymentMethods->data[0]->id,
            'description' => $request->description,
            'off_session' => true,
            'confirm' => true,
        ]);
    }

    private function getOrCreateStripeCustomerId()
    {
        $user = \Auth::user();

        if (!$user->stripe_id) {
            $customer = app('stripe')->customers->create([
                'email' => $user->email,
                'name' => $user->name,
                'description' => "Customer for user ID: {$user->id}",
            ]);
            $user->update(['stripe_id' => $customer->id]);
            return $customer->id;
        }
        
        return $user->stripe_id;
    }
}
