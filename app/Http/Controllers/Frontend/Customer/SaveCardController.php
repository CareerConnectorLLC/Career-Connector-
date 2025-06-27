<?php

namespace App\Http\Controllers\Frontend\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SaveCardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = $request->user();
        
        $stripeCustomerId = $user->stripe_id;

        if (!$stripeCustomerId) {
            $customer = app('stripe')->customers->create([
                'phone' => $user->phone,
                'email' => $user->email,
                'name' => $user->name,
                'description' => "Customer for user ID: {$user->id}",
            ]);
            $stripeCustomerId = $customer->id;

            $user->update(['stripe_id' => $stripeCustomerId]);
        }

        $paymentMethod = app('stripe')->paymentMethods->attach(
            $request->paymentMethodId,
            ['customer' => $stripeCustomerId]
        );

        app('stripe')->customers->update(
            $stripeCustomerId,
            ['invoice_settings' => ['default_payment_method' => $paymentMethod->id]]
        );
    }
}
