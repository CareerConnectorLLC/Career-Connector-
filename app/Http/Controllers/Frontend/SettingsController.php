<?php

namespace App\Http\Controllers\Frontend;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $site_settings = SiteSetting::find(1);

        return response()->json([
            'settings' => $site_settings
        ]);
    }
}
