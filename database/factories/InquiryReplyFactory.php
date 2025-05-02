<?php

namespace Database\Factories;

use App\Models\Inquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InquiryReply>
 */
class InquiryReplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reply' => $this->faker->paragraphs(2, true),
            'Inquiry_id' => Inquiry::inRandomOrder()->first()->id,
        ];
    }
}
