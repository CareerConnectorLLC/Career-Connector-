<?php

namespace App\Http\Controllers\Frontend\Customer\Onboard;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class PersonalInfoController extends Controller
{
    public function index(Request $request)
    {
        $user = User::select('id','name','email', 'location')->find($request->session()->get('user_onboard')['id']);

        return Inertia::render('Frontend/onboarding/client/PersonalInfo', [
            'user' => $user,
        ]);
    }

    public function store(Request $request)
    {
        $user = User::find($request->session()->get('user_onboard')['id']);
        
        $validatedData = $request->validate([
            'email' => [
                'required',
                'email',
                'regex:/^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                Rule::unique('users')->ignore($user->id),
            ],
            'location' => [
                'required',
                'string'
            ],
            'country' => [
                'required',
                'string',
            ],
            'state' => [
                'required',
                'string',
            ],
            'zip_code' => [
                'required',
                'numeric'
            ],
            'date_of_birth' => [
                'nullable',
                'date'
            ]
        ]);

        $user->update(['location' => $validatedData['location']]);

        $user->profile()->create([
            'country' => $validatedData['country'],
            'state' => $validatedData['state'],
            'zip_code' => $validatedData['zip_code'],
            'date_of_birth' => now()->parse($validatedData['date_of_birth'])->format('Y-m-d')
        ]);

        return to_route('frontend.onboard.client.payment-info.index');
    }
}
