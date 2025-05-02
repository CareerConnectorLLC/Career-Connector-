<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminUser = User::create([
            'first_name' => "Admin",
            'last_name' => "Admin",
            'email' => "admin@admin.com",
            'password' => "admin@01",
            'active' => true
        ]);
        $superAdminUser->assignRole('SUPER-ADMIN');

        $demoUser = User::create([
            'first_name' => "Demo",
            'last_name' => "User",
            'email' => "demouser@yopmail.com",
            'phone'=> "8956756245",
            'gender'=> "male",
            'password' => "11111111",
            'active' => true
        ]);

        $demoUser->assignRole('USER');

        User::factory(10)->create()->each(function ($user) {
            $user->assignRole('USER');
        });

        User::factory(10)->create()->each(function ($user) {
            $user->assignRole('SERVICE-PROVIDER');
        });

    }
}
