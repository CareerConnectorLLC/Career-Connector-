<?php

namespace Database\Factories;

use App\Models\{User,Service};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProviderServiceDetail>
 */
class ProviderServiceDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = User::role('SERVICE-PROVIDER')->pluck('id')->all();

        $services = Service::where('active', true)->pluck('id')->all();

        return [
            'provider_id' => $users[rand(0, count($users) - 1)],
            'service_id' => $services[rand(0,19)],
            'location' => fake()->streetAddress(),
            'description' => fake()->text(rand(100, 200)),
            'price' => fake()->numberBetween(1000, 10000),
        ];
    }
}
