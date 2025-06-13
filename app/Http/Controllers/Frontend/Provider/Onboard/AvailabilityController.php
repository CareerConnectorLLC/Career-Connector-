<?php

namespace App\Http\Controllers\Frontend\Provider\Onboard;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\HasAtLeastOneAvailableTime;

class AvailabilityController extends Controller
{
    public function index(Request $request)
    {
        $dates = [];        
        $periods = \Carbon\CarbonPeriod::create(now()->startOfWeek()->subDay()->format('Y-m-d'), 7);
        
        foreach ($periods as $key => $value) {
            $dates[$value->shortDayName] = $this->timePeriods($value);
        }

        return Inertia::render('Frontend/onboarding/provider/Availability', [
            'availability' => $dates,
            'current_url' => $request->url(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'timings' => ['required', 'array', new HasAtLeastOneAvailableTime()],
            'timings.*' => 'array',
            'timings.*.*' => ['nullable', 'string', 'regex:/^(0[1-9]|1[0-2]):[0-5][0-9]\s(am|pm)$/i'],
        ]);

        $user = User::select('id','name')->find(session('user_onboard')['id']);

        $timings = collect($request->input('timings'))->map(fn ($item) => count($item) ? $item : null)->toJson();

        $user->availability()->create([
            'timings' => $timings
        ]);

        $request->session()->forget('services');
        $request->session()->forget('user_onboard');
        return to_route('frontend.onboard.success.page');
    }

    private function timePeriods($day)
    {
        $startPeriod = $day->parse('7:00');
        $endPeriod = $day->parse('17:00');

        $periods = \Carbon\CarbonPeriod::create($startPeriod, '1 hour', $endPeriod);
        $hours = [];

        foreach ($periods as $key => $value) {
            $hours[] = $value->format('h:i a');
        }

        return $hours;
    }
}
