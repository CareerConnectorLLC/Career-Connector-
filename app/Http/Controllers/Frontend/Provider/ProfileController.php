<?php

namespace App\Http\Controllers\Frontend\Provider;

use Inertia\Inertia;
use Illuminate\Http\Request;
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
        //
    }
}
