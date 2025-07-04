<?php

namespace Database\Seeders;

use App\Models\CommissionSetting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CommissionSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CommissionSetting::create([
            'booking_charge' => 5,
            'service_charge' => 10,
        ]);
    }
}
