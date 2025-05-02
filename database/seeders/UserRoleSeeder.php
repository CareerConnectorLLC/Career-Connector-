<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userTypes = [
            ['name' => 'SUPER-ADMIN'],
            ['name' => 'USER'],
            ['name' => 'SERVICE-PROVIDER'],
        ];

        foreach ($userTypes as $userType) {
            Role::create([
                'name' => $userType['name'],
                'guard_name' => 'web',
            ]);
        }
    }
}
