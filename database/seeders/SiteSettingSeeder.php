<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $socialHandles = collect([
            [
                'platform' => 'facebook',
                'icon' => 'facebook',
                'url' => 'https://facebook.com',
            ],
            [
                'platform' => 'instagram',
                'icon' => 'instagram',
                'url' => 'https://instagram.com',
            ],
            [
                'platform' => 'linkedin',
                'icon' => 'linkedin',
                'url' => 'https://linkedin.com',
            ],
        ]);
        
        SiteSetting::create([
            'address' => '789 Pine Ln, Villagetown, USA',
            'phone' => '+1-555-111-2222',
            'email' => 'sales@example.com',
            'social_handles' => $socialHandles->toJson(),
        ]);
    }
}
