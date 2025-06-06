<?php

namespace App\Http\Controllers\Frontend;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\{Service,ProviderServiceDetail};
use App\Http\Controllers\Controller;

class ProviderListingController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $availableServices = Service::select([
            'id', 'name'
        ])->where('active', true)->get();

        $providersQuery = ProviderServiceDetail::query();

        $providersQuery->with([
            'provider:id,name',
            'service:id,name'
        ]);

        if ($request->has('services')) {
            $providersQuery->whereIn('service_id', $request->get('services'));
        }

        if ($request->has('price') && $request->get('price') > 0) {
            $providersQuery->where('price', '<=', $request->get('price'));
        }

        $serviceProviders = $providersQuery->latest()->paginate(15)->withQueryString();

        return Inertia::render('Frontend/ProviderListing', [
            'services' => $availableServices,
            'providers' => $serviceProviders,
            'service_ids' => $request->get('services') ?? [],
            'pricing' => $request->get('price') ?? 0,
        ]);
    }
}
