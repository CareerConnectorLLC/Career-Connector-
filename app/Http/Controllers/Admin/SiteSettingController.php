<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;


class SiteSettingController extends Controller
{
    public function index()
    {   
        try {
            $site_setting = SiteSetting::firstOrFail();
            return Inertia::render('Admin/site-setting/CreateEdit', compact('site_setting'));
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function update(Request $request)
    {   
        // dd($request->all());
        $regex = '/^(https?:\/\/)?([\w\-]+\.)+[\w\-]+(\/[\w\-._~:?#[\]@!$&\'()*+,;=]*)?$/i';
        $request->validate([
            'address'                   => 'required|string',
            'phone'                     => 'required|digits_between:10,15',
            'email'                     => 'required|email:rfc,dns',
            'social_handles'            => 'required',
            'social_handles.*.platform' => 'required',
            'social_handles.*.url'      => ['required','regex:'.$regex],
            'social_handles.*.icon'     => 'required',
        ],[
            'social_handles.*.platform.required' => 'This field is required.',
            'social_handles.*.url.required' => 'This field is required.',
            'social_handles.*.url.regex' => 'Please enter a valid URL.',
            'social_handles.*.icon.required' => 'This field is required.',
        ]);

        try {

            $site_setting = SiteSetting::firstOrFail();
            $site_setting->address = $request->address;
            $site_setting->phone = $request->phone;
            $site_setting->email = $request->email;
            $site_setting->social_handles = $request->social_handles;
            $site_setting->update();

            return redirect()->back()->with('success', 'The Site Setting has been updated successfully');

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }
}
