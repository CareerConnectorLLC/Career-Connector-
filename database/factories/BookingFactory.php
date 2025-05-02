<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   
        $client = User::inRandomOrder()->first();
        $provider = User::where('id', '!=', $client->id)->inRandomOrder()->first();
        
        return [
            'status' => $this->faker->randomElement(['pending', 'in-progress', 'cancelled', 'completed']),
            'service_id' => Service::inRandomOrder()->first()->id,
            'client_id' => $client->id,
            'provider_id' => $provider->id,
        ];
    }
}
