<?php

namespace Database\Seeders;
use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'setting_name' => 'Overtime Calculation',
            'value' => '30 minutes',
            'description' => 'OT start calculate after shift end 30 minutes'
        ]);

        Setting::create([
            'setting_name' => 'Late Threshold Minutes',
            'value' => '5 minutes',
            'description' => 'All late to clock in 5 minutes'
        ]);
    }
}
