<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            [
                'name' => 'Web Development',
                'description' => fake()->text(200),
            ],
            [
                'name' => 'Graphic Design',
                'description' => fake()->text(200),
            ],
            [
                'name' => 'Content Writing',
                'description' => fake()->text(200),
            ],
            [
                'name' => 'Digital Marketing',
                'description' => fake()->text(200),
            ],
            [
                'name' => 'Virtual Assistance',
                'description' => fake()->text(200),
            ],
        ])->each(function ($item) {
            \App\Models\Category::create($item);
        });
    }
}
