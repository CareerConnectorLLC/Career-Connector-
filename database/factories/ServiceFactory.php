<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   
        $categories_ids = Category::pluck('id')->toArray();
        return [
            'name'          => $this->faker->word,
            'slug'          => $this->faker->slug(),
            'description'   => $this->faker->sentence,
            'category_id'   => $this->faker->randomElement($categories_ids),
        ];
    }
}
