<?php

namespace App\Http\Controllers\Frontend\Provider\Onboard;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServiceDetailController extends Controller
{
    public function index(Request $request)
    {
        $details = null;
        $services = session('services');

        $serviceDetails = \DB::table('provider_service_details')
            ->where('provider_id', session('user_onboard')['id'])
            ->whereIn('service_id', collect($services)->pluck('id')->toArray())
            ->get();

        if ($serviceDetails->count()) {
            foreach ($serviceDetails as $key => $value) {
               $details['location'][$value->service_id] = $value->location; 
               $details['description'][$value->service_id] = $value->description; 
               $details['price'][$value->service_id] = $value->price / 100; 
            }
        }

        return Inertia::render('Frontend/onboarding/provider/ServiceDetail', [
            'services' => $services,
            'current_url' => $request->url(),
            'details' => $details,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'location' => ['required', 'array', 'min:1'],
            'location.*' => ['required', 'string'],
            'description' => ['required', 'array', 'min:1'],
            'description.*' => ['required', 'string'],
            'price' => ['required', 'array', 'min:1'],
            'price.*' => ['required', 'numeric'],
        ], [
            'location.*' => 'The location field is required',
            'description.*' => 'The description field is required',
            'price.*' => 'The price field is required'
        ]);

        $user = User::select('id','name')->find(session('user_onboard')['id']);
        
        $details = $user->providerServiceDetails;

        if ($details->count()) {
            $user->providerServiceDetails()->delete();
        }

        foreach ($validatedData['location'] as $key => $value) {
            $user->providerServiceDetails()->create([
                'service_id' => $key,
                'location' => $value,
                'description' => $validatedData['description'][$key],
                'price' => (int) $validatedData['price'][$key] * 100,
            ]);
        }

        return to_route('frontend.onboard.provider.document-upload.index');
    }
}
