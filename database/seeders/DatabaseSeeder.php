<?php

namespace Database\Seeders;

use App\Models\Availability;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Booking;
use App\Models\Inquiry;
use App\Models\ProviderService;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserRoleSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(CmsTableSeeder::class);
        $this->call(FaqSeeder::class);
        $this->call(BlogSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(SiteSettingSeeder::class);
        Availability::factory(10)->create();
        ProviderService::factory()->count(10)->create();
        Booking::factory()->count(40)->create();
        Inquiry::factory()->count(15)->create();




        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
