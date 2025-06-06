<?php

namespace App\Http\Controllers\Frontend\Provider\Onboard;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Category,Service,User};

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
            ->where('provider_personal_infos.provider_id', session('user_onboard')['id'])
            ->get();

        $personalData = null;

        if ($personalInfoData->count()) {
            $categoryIds = $personalInfoData->pluck('category_id')->unique()->values()->toArray();

            $personalData['category'] = $categoryIds;
            $personalData['experience'] = $personalInfoData->unique('exp_year')->pluck('exp_year')->first();
            $personalData['services'] = collect(session('services'))->pluck('id')->all();
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
            'services' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    $services = Service::select('category_id')->whereIn('id', $value)->get();

                    $categoryIds = array_filter(request()->input('category'), function ($cat_id) use($services) {
                        return !in_array($cat_id, $services->pluck('category_id')->toArray());
                    });

                    if (is_array($categoryIds) && !empty($categoryIds)) {
                        $categories = Category::select('name')->whereIn('id', $categoryIds)->get();

                        $messages = $categories->map(function ($category) {
                            return "You've not selected any services from {$category->name}";
                        });

                        $fail(implode('<br/>', $messages->all()));
                    }
                }
            ],
        ]);

        $user = User::select('id','name')->find(session('user_onboard')['id']);

        $details = \DB::table('provider_personal_infos')->where('provider_id', auth()->id())->get();

        if ($details->count()) {
            \DB::table('provider_personal_infos')->where('provider_id', auth()->id())->delete();
        }

        collect($validatedData['services'])->each(function ($item) use($validatedData, $user) {
            $user->providerPersonalInfos()->create([
                'service_id' => $item,
                'exp_year' => $validatedData['experience'],
            ]);
        });

        $services = Service::select('id','name')
            ->where('active', true)
            ->whereIn('id', $validatedData['services'])
            ->get();

        session()->put('services', $services->toArray());
        return to_route('frontend.onboard.provider.service-details.index');
    }
}
