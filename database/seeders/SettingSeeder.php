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
            'value' => '15 minutes',
            'description' => 'OT start calculate after shift end 15 minutes'
        ]);

        Setting::create([
            'setting_name' => 'Late Threshold Minutes',
            'value' => '5 minutes',
            'description' => 'Allow late in 5 minutes'
        ]);

        Setting::create([
            'setting_name' => 'OT Allowance',
            'value' => 'RM6',
            'description' => 'RM6 per hour'
        ]);

        Setting::create([
            'setting_name' => 'Notice Board',
            'value' => 'Everyone should help each other if others needed help.',
            'description' => 'Motivation'
        ]);
    }
}
