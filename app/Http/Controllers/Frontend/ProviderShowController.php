<?php

namespace App\Http\Controllers\Frontend;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{ProviderServiceDetail};

class ProviderShowController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id)
    {
        $providerData = ProviderServiceDetail::with([
            'provider:id,name,profile_photo_path,location',
            'provider.availability',
            'provider.providerSocialLinks:provider_id,name,url',
            'service:id,name'
        ])->find($id);

        $timings = $providerData->provider->availability->timings;

        return Inertia::render('Frontend/ProviderShow', [
            'provider' => $providerData,
            'timings' => $timings,
            'schedules' => $timings,
            'has_payment_methods' => $this->hasPaymentMethods() ? true : false,
            'has_pending_or_confirmed_booking_today' => $this->hasPendingOrConfirmedBookingToday() ? true : false,
        ]);
    }

    private function hasPaymentMethods()
    {
        if (!auth()->check() || !auth()->user()->hasRole('USER') || empty(auth()->user()->stripe_id)) {
            return false;
        }

        $paymentMethods = app('stripe')->customers->allPaymentMethods(
            auth()->user()->stripe_id,
            ['type' => 'card']
        );

        return !empty($paymentMethods->data);
    }

    private function hasPendingOrConfirmedBookingToday()
    {
        if (!auth()->check()) {
            return false;
        }

        return \App\Models\Booking::where('client_id', auth()->id())
            ->whereIn('status', ['Pending', 'Confirmed'])
            ->whereDate('start_date', today())
            ->exists();
    }
}
