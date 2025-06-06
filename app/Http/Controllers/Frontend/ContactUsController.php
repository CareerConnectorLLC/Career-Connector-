<?php

namespace App\Http\Controllers\Frontend;

use Inertia\Inertia;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactUsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if ($request->isMethod('post')) {
            $validatedData = $request->validate([
                'full_name' => [
                    'required',
                ],
                'email' => [
                    'required',
                    'email',
                    'regex:/^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                ],
                'message' => [
                    'required'
                ]
            ]);

            $role = \Spatie\Permission\Models\Role::where('id', 1)->with('users')->first();
            $user = $role->users->first();

            \Mail::send(
                'mail.contact_us', [
                    'data' => $validatedData
                ], function ($message) use($user) {
                    $message->to($user->email);
                    $message->subject('User Enquiry');
                }
            );

            session()->flash('success', 'Your message has been send successfully!'); 
        }

        $site_settings = SiteSetting::get();

        return Inertia::render('Frontend/ContactUs', [
            'site_settings' => $site_settings->last()
        ]);
    }
}
