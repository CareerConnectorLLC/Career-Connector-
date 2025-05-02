<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Expertise;
use App\Models\ExpertiseProvider;
use App\Models\Location;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Factory;


class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        Category::factory(10)->create();
        Service::factory(10)->create();
        $faker = Factory::create();
        $service_ids = Service::pluck('id')->toArray();
        $user_ids = User::role('SERVICE-PROVIDER')->pluck('id')->toArray();

        foreach (range(1, 10) as $index) {
            Expertise::create([
                'name'          => $faker->word,
                'slug'          => $faker->slug(),
                'service_id'    => $faker->randomElement($service_ids),
                'created_at'    => now(),
                'updated_at'    => now()
            ]);
        }
        foreach (range(1, 10) as $index) {
            Location::create([
                'name'          => $faker->city(),
                'slug'          => $faker->slug(),
                'lat'           => $faker->latitude(),
                'long'           => $faker->longitude(),
                'created_at'    => now(),
                'updated_at'    => now()
            ]);
        }
        $expertise_ids = Expertise::pluck('id')->toArray();
        foreach (range(1, 10) as $index) {
            ExpertiseProvider::create([
                'user_id'       => $faker->randomElement($user_ids),
                'expertise_id'  => $faker->randomElement($expertise_ids),
                'created_at'    => now(),
                'updated_at'    => now()
            ]);
        }
    }
}
