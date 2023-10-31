<?php

namespace Database\Seeders;

use App\Models\Duty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DutySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Duty::create([
            'duty_name' => 'Sweep & Mop'
        ]);
    }
}
