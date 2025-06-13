<?php

namespace Database\Seeders;

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
        $this->call(\Database\Seeders\RoleSeeder::class);

        $user_a = User::create([
            'name' => "Administrator",
            'email' => "admin@admin.com",
            'password' => "admin@01",
            'active' => true
        ]);

        $user_b = User::create([
            'name' => "Nyah Jones",
            'email' => "olson.jocelyn@example.org",
            'password' => "password",
            'active' => true
        ]);

        $user_c = User::create([
            'name' => "Brice Friesen",
            'email' => "norma.little@example.com",
            'password' => "password",
            'active' => true
        ]);

        $user_a->assignRole('SUPER-ADMIN');
        $user_b->assignRole('USER');
        $user_c->assignRole('SERVICE-PROVIDER');

        $this->call(\Database\Seeders\UserSeeder::class);
        $this->call(\Database\Seeders\BlogSeeder::class);
        $this->call(\Database\Seeders\CategorySeeder::class);
        $this->call(\Database\Seeders\ServiceSeeder::class);
        $this->call(\Database\Seeders\SiteSettingSeeder::class);
        $this->call(\Database\Seeders\ProviderServiceDetailSeeder::class);
        $this->call(\Database\Seeders\ProviderAvailabilitySeeder::class);
    }
}
