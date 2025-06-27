<?php

namespace App\Http\Controllers\Frontend\Customer\Onboard;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentInfoController extends Controller
{
    public function index(Request $request)
    {
        $user = User::with('profile')->select(
            'id','name','email', 'stripe_id'
        )->find(
            $request->session()->get('user_onboard')['id']
        );
        
        return Inertia::render('Frontend/onboarding/client/PaymentInfo', [
            'user' => $user,
            'stripe_key' => env('STRIPE_KEY'),
        ]);
    }

    public function store(Request $request)
    {
        
        $user = User::find($request->session()->get('user_onboard')['id']);

        $stripeCustomerId = $user->stripe_id;

        $paymentMethod = app('stripe')->paymentMethods->attach(
            $request->payment_method_id,
            ['customer' => $stripeCustomerId]
        );
        
        app('stripe')->customers->update(
            $stripeCustomerId,
            ['invoice_settings' => ['default_payment_method' => $paymentMethod->id]]
        );

        $request->session()->forget('user_onboard');

        return to_route('frontend.onboard.success.page');
    }
}
