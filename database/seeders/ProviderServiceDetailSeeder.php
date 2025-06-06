<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProviderServiceDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProviderServiceDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProviderServiceDetail::factory(100)->create();
    }
}
