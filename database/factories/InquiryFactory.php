<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inquiry>
 */
class InquiryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->randomElement([
                'skitters0302@gmail.com',
                'devwebskitters@gmail.com',
            ]),
            'phone' => $this->faker->phoneNumber(),
            'message' => $this->faker->randomElement([
                'Hi, I would like to know more about your services.',
                'Can someone contact me regarding a project?',
                'I have a question about pricing. Please get back to me.',
                'Is there a way to schedule a call with your team?',
                'I’m interested in collaborating. Let’s talk!',
                'I tried signing up but ran into an issue. Help?',
                'Do you offer discounts for nonprofits or students?',
            ]),
            'created_at' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'updated_at' => now(), // or same as created_at if preferred
        ];
    }
}
