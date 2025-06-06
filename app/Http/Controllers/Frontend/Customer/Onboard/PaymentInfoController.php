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

        $paymentIntent = app('stripe')->setupIntents->create([
            'customer' => $user->stripe_id,
            'payment_method_types' => ['card'],
        ]);
        
        return Inertia::render('Frontend/onboarding/client/PaymentInfo', [
            'user' => $user,
            'client_secret' => $paymentIntent->client_secret,
            'stripe_key' => env('STRIPE_KEY'),
        ]);
    }

    public function store(Request $request)
    {
        app('stripe')->paymentMethods->attach(
            $request->input('payment_method_id'),
            ['customer' => $request->stripe_id],
        );

        $request->session()->forget('user_onboard');

        return to_route('frontend.onboard.success.page');
    }
}
