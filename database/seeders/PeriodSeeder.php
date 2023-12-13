<?php

namespace Database\Seeders;

use App\Models\Period;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Period::create([
            'period_name' => 'Opening'
        ]);

        Period::create([
            'period_name' => 'Closing'
        ]);

        Period::create([
            'period_name' => 'Lunch'
        ]);

        Period::create([
            'period_name' => 'Dinner'
        ]);
    }
}
