<?php

namespace Database\Seeders;

use App\Models\Locket;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Locket::create(['code' => 'A']);
        Locket::create(['code' => 'B']);
        Locket::create(['code' => 'C']);
        Locket::create(['code' => 'D']);
    }
}
