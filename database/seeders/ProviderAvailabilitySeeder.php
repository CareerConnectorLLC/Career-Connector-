<?php

namespace Database\Seeders;

use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;
use App\Models\{User,ProviderAvailability};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProviderAvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::role('SERVICE-PROVIDER')->pluck('id');
        
        $timings = [];

        $periods = CarbonPeriod::create(now()->startOfWeek()->subDay()->format('Y-m-d'), 7);

        foreach ($periods as $key => $day) {
            $timings[$day->shortDayName] = (!$day->isWeekend()) ? $this->timePeriods($day) : null;
        }

        $users->each(function ($user, $key) use($timings) {
            ProviderAvailability::create([
                'provider_id' => $user,
                'timings' => collect($timings)->toJson(),
            ]);
        });
    }

    private function timePeriods($day)
    {
        $startPeriod = $day->parse('7:00');
        $endPeriod = $day->parse('17:00');

        $periods = CarbonPeriod::create($startPeriod, '1 hour', $endPeriod);
        $hours = [];

        foreach ($periods as $key => $value) {
            $hours[] = $value->format('h:i a');
        }

        return $hours;
    }
}
