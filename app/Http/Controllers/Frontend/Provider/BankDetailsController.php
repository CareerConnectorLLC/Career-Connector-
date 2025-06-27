<?php

namespace App\Http\Controllers\Frontend\Provider;

use Illuminate\Http\Request;
use App\Models\ProviderBankDetail;
use App\Http\Controllers\Controller;

class BankDetailsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'account_holder_name' => ['required', 'string'],
            'account_number' => ['required', 'regex:/^[0-9]{9,18}$/i'],
            'routing_number' => ['required']
        ]);

        try {
            $token = app('stripe')->tokens->create([
                'bank_account' => [
                    'country' => 'US',
                    'currency' => 'usd',
                    'account_holder_name' => $request->account_holder_name,
                    'account_holder_type' => 'individual',
                    'account_number' => $request->account_number,
                    'routing_number' => $request->routing_number,
                ],
            ]);

            $bankAccount = app('stripe')->accounts->createExternalAccount(
                $request->user()->stripe_id, ['external_account' => $token->id]
            );

            return response()->json([
                'message' => 'Bank details saved successfully.',
                'data' => $bankAccount 
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error while saving bank details.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $stripe = app('stripe');
        
        $stripe->accounts->updateExternalAccount(
            $request->user()->stripe_id, $id, [
                'default_for_currency' => true
            ]
        );
    }

    public function destroy(Request $request, $id)
    {
        $stripe = app('stripe');

        $stripe->accounts->deleteExternalAccount(
            $request->user()->stripe_id, $id
        );
    }

    public function stripeSetup(Request $request)
    {
        $stripe = app('stripe');

        $account = $stripe->accounts->create([
            'country' => 'US',
            'type' => 'custom',
            'email' => $request->user()->email,
            'capabilities' => [
                'card_payments' => ['requested' => true],
                'transfers' => ['requested' => true],
            ],
        ]);

        $accountLink = $stripe->accountLinks->create([
            'account' => $account->id,
            'refresh_url' => url('/provider-profile'),
            'return_url' => url('/provider-profile'),
            'type' => 'account_onboarding',
            'collection_options' => ['fields' => 'eventually_due'],
        ]);

        $request->user()->update(['stripe_id' => $account->id]);
        return \Inertia\Inertia::location($accountLink->url);
    }
}
