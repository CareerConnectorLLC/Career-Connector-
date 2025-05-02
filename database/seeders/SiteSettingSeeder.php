<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Faker\Factory as Factory;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        SiteSetting::create([
            'address' => '2972 Westheimer Rd. Santa Ana, Illinois 85486',
            'phone' => '(406) 555-0120',
            'email' => 'sara.cruz@example.com',
                        'social_handles' => json_encode([
                [
                    'platform' => 'facebook',
                    'url' => 'https://www.facebook.com/',
                    'icon' => 'https://www.facebook.com/',
                ],
                [
                    'platform' => 'X',  // Formerly known as Twitter
                    'url' => 'https://x.com/',
                    'icon' => 'https://x.com/',
                ],
                [
                    'platform' => 'instagram',
                    'url' => 'https://www.instagram.com/',
                    'icon' => 'https://www.instagram.com/',
                ],
                [
                    'platform' => 'LinkdIn',
                    'url' => 'https://www.linkedin.com/',
                    'icon' => 'https://www.linkedin.com/',
                ],
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
