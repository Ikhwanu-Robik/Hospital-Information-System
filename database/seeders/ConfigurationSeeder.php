<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder {
    public function run(): void 
    {
        Setting::create(['key' => 'doctor-ping-interval', 'value' => 1500]);
    }
}