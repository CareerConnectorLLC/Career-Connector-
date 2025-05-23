<?php

namespace App\Http\Controllers\Frontend\Provider\Onboard;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\{Category,Service};
use App\Http\Controllers\Controller;

class PersonalInfoController extends Controller
{
    public function index(Request $request)
    {
        if (!session()->has('services')) {
            session()->put('services', []);
        }
        
        $categories = Category::select('id','name')
            ->where('active', true)
            ->orderBy('id', 'desc')
            ->get();

        $personalInfoData = \DB::table('provider_personal_infos')
            ->select('provider_personal_infos.*', 'services.category_id')
            ->join('services', 'services.id', 'provider_personal_infos.service_id')
            ->where('provider_personal_infos.provider_id', auth()->id())
            ->get();

        $personalData = null;

        if ($personalInfoData->count()) {
            foreach ($personalInfoData as $value) {
                $personalData['category'][] = $value->category_id;
                $personalData['services'][] = $value->service_id;
                $personalData['experience'] = $value->exp_year;
            }

            $personalData['category'] = array_unique($personalData['category']);
        }
            
        return Inertia::render('Frontend/onboarding/provider/PersonalInfo', [
            'categories' => $categories,
            'current_url' => $request->url(),
            'personal_data' => $personalData,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category' => ['required', 'array'],
            'experience' => ['required', 'numeric'],
            'services' => ['required', 'array'],
        ]);

        $details = \DB::table('provider_personal_infos')->where('provider_id', auth()->id())->get();

        if ($details->count()) {
            \DB::table('provider_personal_infos')->where('provider_id', auth()->id())->delete();
        }

        collect($validatedData['services'])->each(function ($item) use($validatedData) {
            auth()->user()->providerPersonalInfos()->create([
                'service_id' => $item,
                'exp_year' => $validatedData['experience'],
            ]);
        });

        $services = Service::select('id','name')
            ->where('active', true)
            ->whereIn('id', $validatedData['services'])
            ->get();

        session()->put('services', $services->toArray());
        return to_route('frontend.onboard.provider-service-details.index');
    }
}
