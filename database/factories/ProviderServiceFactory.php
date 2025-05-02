<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProviderService>
 */
class ProviderServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'service_id' => Service::inRandomOrder()->first()->id,
            'location_id' => Location::inRandomOrder()->first()->id,
            'price' => $this->faker->numberBetween(50, 500), 
        ];
    }
}
