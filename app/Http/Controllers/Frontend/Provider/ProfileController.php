<?php

namespace App\Http\Controllers\Frontend\Provider;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\{Service,UserProfile};

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->load([
            'profile',
            'providerServiceDetails.service',
            'providerDocuments.service:id,name'
        ]);

        $services = $user->providerServiceDetails;
        
        $documents = $user->providerDocuments;

        if ($request->user()->stripe_id) {
            $bank_accounts = app('stripe')->accounts->allExternalAccounts(
                $request->user()->stripe_id, [
                    'object' => 'bank_account'
                ]
            );
        }

        return Inertia::render('Frontend/ProviderProfile', [
            'user' => $user,
            'services' => $services,
            'bank_accounts' => $bank_accounts->data ?? [],
            'all_services' => Service::select(['id','name'])->get(),
            'documents' => $documents,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => [
                'required',
                'string'
            ],
            'email' => [
                'required',
                'email',
                'regex:/^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                Rule::unique('users')->ignore(auth()->id()),
            ],
            'location' => [
                'required',
                'string'
            ],
            'phone' => [
                'required',
                'numeric',
                Rule::unique('users')->ignore(auth()->id()),
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
            'profile_pic' => [
                'nullable',
                'image'
            ],
            'date_of_birth' => [
                'nullable',
                'date'
            ]
        ]);

        $filePath = $request->user()->profile_photo_path;

        // if new file is uploaded
        if ($request->hasFile('profile_pic')) {
            // delete old file
            if ($filePath) {
                \Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('profile_pic')->store('profile', 'public');
        } else if ($request->input('profile_pic') === null) {
            // if profile pic is removed
            if ($filePath) {
                \Storage::disk('public')->delete($filePath);
            }
            $filePath = null;
        }

        $request->user()->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'location' => $validatedData['location'],
            'profile_photo_path' => $filePath,
        ]);

        UserProfile::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'country' => $validatedData['country'],
                'state' => $validatedData['state'],
                'zip_code' => $validatedData['zip_code'],
                'date_of_birth' => now()->parse($validatedData['date_of_birth'])->format('Y-m-d')
            ]
        );

        $request->session()->flash('success', 'Profile updated successfully!');
    }
}
