<?php

namespace App\Http\Controllers\Frontend\Provider;

use Inertia\Inertia;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->load('profile');
        
        return Inertia::render('Frontend/ProviderProfile', [
            'user' => $user
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

        $filePath = null;

        if ($request->user()->profile_photo_path) {
            $filePath = $request->user()->profile_photo_path;
        }

        if (!is_null($request->file('profile_pic'))) {
            if (!is_null($request->user()->profile_photo_path)) {
                unlink(storage_path('/app/public/'.$request->user()->profile_photo_path));
            }
            
            $file = $request->file('profile_pic');
            $filePath = \Storage::putFile('profile', $file, 'public');
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
    }
}
