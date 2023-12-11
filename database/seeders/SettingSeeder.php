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
            'setting_name' => 'Overtime Calculation (in minutes)',
            'value' => '15',
            'description' => 'OT start calculate after shift end 15 minutes'
        ]);

        Setting::create([
            'setting_name' => 'Late Threshold (in minutes)',
            'value' => '5',
            'description' => 'Allow late in 5 minutes'
        ]);

        Setting::create([
            'setting_name' => 'OT Allowance (in RM)',
            'value' => '6',
            'description' => 'RM6 per hour'
        ]);

    }
}
