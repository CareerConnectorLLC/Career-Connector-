<?php

namespace App\Http\Controllers\Frontend\Provider;

use App\Http\Controllers\Controller;
use App\Models\CommissionSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PaymentHistoryController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all completed bookings.
        $payments = $request->user()->providerBookings()
            ->select(['id', 'price', 'booking_number', 'client_id', 'service_id', 'start_date', 'end_date', 'status', 'paid_out_at'])
            ->with('service:id,name', 'client:id,name')
            ->where('status', 'Completed')
            ->latest()
            ->get();

        // Calculate gross earnings (in cents) from unpaid bookings.
        $grossEarnings = $payments->whereNull('paid_out_at')->sum('price');

        // Fetch commission percentage.
        $commissionPercentage = CommissionSetting::first()->service_charge ?? 0;

        // Calculate commission amount (in cents).
        $commissionAmount = floor(($grossEarnings * $commissionPercentage) / 100);

        // Calculate net earnings for the provider.
        $netEarnings = $grossEarnings - $commissionAmount;

        // Now, modify the payments collection for frontend display
        $displayPayments = $payments->map(function ($payment) {
            $payment->price = $payment->price / 100; // Convert price to dollars for display
            return $payment;
        });

        return Inertia::render('Frontend/provider/PaymentHistory', [
            'payments' => $displayPayments,
            'totalEarnings' => $netEarnings / 100, // Pass net earnings in dollars
            'commissionPercentage' => $commissionPercentage, // Add this line
        ]);
    }

    /**
     * Initiate a payout to the provider.
     */
    public function initiatePayout(Request $request): RedirectResponse
    {
        $provider = $request->user();

        // Validate the incoming booking IDs (now optional)
        $request->validate([
            'booking_ids' => 'nullable|array',
            'booking_ids.*' => 'exists:bookings,id', // Ensure each ID exists
        ]);

        $bookingIds = $request->input('booking_ids');

        // 1. Get the bookings to be paid out based on selected IDs or all unpaid bookings
        $query = $provider->providerBookings()
            ->where('status', 'Completed')
            ->whereNull('paid_out_at');

        if (!empty($bookingIds)) {
            $query->whereIn('id', $bookingIds);
        }

        $bookingsToPayout = $query->get();

        // If specific booking IDs were provided, ensure they all belong to the provider and are eligible
        if (!empty($bookingIds) && $bookingsToPayout->count() !== count($bookingIds)) {
            return redirect()->back()->with('error', 'One or more selected bookings are not eligible for payout or do not belong to you.');
        }

        $grossPayoutAmount = $bookingsToPayout->sum('price');

        if ($grossPayoutAmount <= 0) {
            return redirect()->back()->with('error', 'You have no earnings to withdraw from the selected bookings.');
        }

        // 2. Calculate commission
        $commissionPercentage = CommissionSetting::first()->service_charge ?? 0;
        $commissionAmount = floor(($grossPayoutAmount * $commissionPercentage) / 100);
        $netTransferAmount = $grossPayoutAmount - $commissionAmount;


        // Ensure the provider has a Stripe account connected.
        if (!$provider->stripe_id) {
            return redirect()->back()->with('error', 'You must connect a Stripe account before you can withdraw funds.');
        }

        try {
            // 3. Initialize Stripe and create the transfer for the net amount.
            $stripe = app('stripe');
            $stripe->transfers->create([
                'amount' => $netTransferAmount, // Amount in cents
                'currency' => 'usd',
                'destination' => $provider->stripe_id,
                'transfer_group' => 'PAYOUT_' . $provider->id . '_' . time(),
            ]);

            // 4. If the transfer is successful, mark the bookings as paid out by setting the timestamp.
            $bookingIdsToMarkPaid = $bookingsToPayout->pluck('id');
            $provider->providerBookings()->whereIn('id', $bookingIdsToMarkPaid)->update(['paid_out_at' => now()]);

            return redirect()->back()->with('success', 'Payout initiated successfully! Amount: $' . number_format($netTransferAmount / 100, 2));

        } catch (\Exception $e) {
            Log::error('Stripe Payout Failed for provider ' . $provider->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an issue processing your payout. Please contact support.');
        }
    }
}