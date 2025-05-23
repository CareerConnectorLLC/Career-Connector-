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
            $services = Service::select('id','name')
                ->where('active', true)
                ->whereIn('category_id', $request->category_id)
                ->get();
        }

        return response()->json([
            'data' => $services
        ]);
    }
}
