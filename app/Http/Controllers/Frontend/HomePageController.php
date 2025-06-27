<?php

namespace App\Http\Controllers\Frontend;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\{Service,SeoSetting};
use App\Http\Controllers\Controller;

class HomePageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $services = Service::select(['id', 'name'])->where('active', true)->get();
        
        $seoSetting = SeoSetting::where('page_identifier', 'welcome_page')->first();

        return Inertia::render('Frontend/Home', [
            'services' => $services,
            'seo' => $seoSetting?->toArray(),
            'site_title' => config('app.name'),
        ]);
    }
}
