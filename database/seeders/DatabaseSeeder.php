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

        $user = User::create([
            'name' => "Administrator",
            'email' => "admin@admin.com",
            'password' => "admin@01",
            'active' => true
        ]);

        $user->assignRole('SUPER-ADMIN');

        $this->call(\Database\Seeders\UserSeeder::class);
        $this->call(\Database\Seeders\BlogSeeder::class);
        $this->call(\Database\Seeders\CategorySeeder::class);
        $this->call(\Database\Seeders\ServiceSeeder::class);
    }
}
