<?php

namespace App\Http\Controllers\Frontend;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{ProviderServiceDetail};

class ProviderShowController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id)
    {
        $providerData = ProviderServiceDetail::with([
            'provider:id,name',
            'provider.availability',
            'service:id,name'
        ])->find($id);

        $timings = $providerData->provider->availability->timings;

        return Inertia::render('Frontend/ProviderShow', [
            'provider' => $providerData,
            'timings' => $timings
        ]);
    }
}
