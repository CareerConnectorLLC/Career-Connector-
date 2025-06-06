<?php

namespace App\Http\Controllers\Frontend\Provider;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\{Category,Service};
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OnboardingController extends Controller
{
    public function __invoke(Request $request)
    {
        $services = [];

        if ($request->has('category_id')) {
            $services = Category::select('id','name')
                ->with('services', fn ($q) => $q->select('id','name','category_id')->where('active', true))
                ->where('active', true)
                ->whereIn('id', $request->category_id)
                ->get()
                ->map(function ($category) {
                    return [
                        'category_name' => $category->name,
                        'category_id' => $category->id,
                        'items' => $category->services->map(fn ($service) => [
                            'id' => $service->id,
                            'name' => $service->name
                        ])
                    ];
                });
        }

        return response()->json([
            'data' => $services
        ]);
    }
}
