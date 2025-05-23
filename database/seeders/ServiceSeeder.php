<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Front-end Development',
                'category_id' => 1,
                'description' => fake()->text(250),
            ],
            [
                'name' => 'Back-end Development',
                'category_id' => 1,
                'description' => fake()->text(250),
            ],
            [
                'name' => 'E-commerce Development',
                'category_id' => 1,
                'description' => fake()->text(250),
            ],
            [
                'name' => 'WordPress Development',
                'category_id' => 1,
                'description' => fake()->text(250),
            ],
            [
                'name' => 'Logo Design',
                'category_id' => 2,
                'description' => fake()->text(250),
            ],
            [
                'name' => 'Branding',
                'category_id' => 2,
                'description' => fake()->text(250),
            ],
            [
                'name' => 'Infographics',
                'category_id' => 2,
                'description' => fake()->text(250),
            ],
            [
                'name' => 'Illustration',
                'category_id' => 2,
                'description' => fake()->text(250),
            ],
            [
                'name' => 'Blog Writing',
                'category_id' => 3,
                'description' => fake()->text(250),
            ],
            [
                'name' => 'Copywriting for Ads',
                'category_id' => 3,
                'description' => fake()->text(250),
            ],
            [
                'name' => 'Proofreading',
                'category_id' => 3,
                'description' => fake()->text(250),
            ],
            [
                'name' => 'Technical Writing',
                'category_id' => 4,
                'description' => fake()->text(250),
            ],
            [
                'name' => 'Social Media Marketing',
                'category_id' => 4,
                'description' => fake()->text(250),
            ],
            [
                'name' => 'Search Engine Optimization',
                'category_id' => 4,
                'description' => fake()->text(250),
            ],
            [
                'name' => 'Email Marketing',
                'category_id' => 4,
                'description' => fake()->text(250),
            ],
            [
                'name' => 'Influencer Marketing',
                'category_id' => 4,
                'description' => fake()->text(250),
            ],
            [
                'name' => 'Administrative Support',
                'category_id' => 5,
                'description' => fake()->text(250),
            ],
            [
                'name' => 'Customer Service',
                'category_id' => 5,
                'description' => fake()->text(250),
            ],
            [
                'name' => 'Research Assistance',
                'category_id' => 5,
                'description' => fake()->text(250),
            ],
            [
                'name' => 'Project Management',
                'category_id' => 5,
                'description' => fake()->text(250),
            ],
        ];

        collect($services)->each(function ($service) {
            \App\Models\Service::create($service);
        });
    }
}
