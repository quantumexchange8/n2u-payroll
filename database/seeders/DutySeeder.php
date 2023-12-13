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
            'duty_name' => '(O) Side Dish & Grilling Pan Set Up'
        ]);

        Duty::create([
            'duty_name' => '(O) Bar Preparation & Glass Panel Cleaning'
        ]);

        Duty::create([
            'duty_name' => '(O) All Cleaning (include toilet) & Seats'
        ]);

        Duty::create([
            'duty_name' => '(C) Side Dish & Ducting Clean Up'
        ]);

        Duty::create([
            'duty_name' => '(C) Bar Cleaning'
        ]);

        Duty::create([
            'duty_name' => '(C) Clear Table, Wipe Seats & Dome/Partition'
        ]);

        Duty::create([
            'duty_name' => '(C) Sweep & Mop'
        ]);

        Duty::create([
            'duty_name' => '(C) Ducting Cleaning'
        ]);

        Duty::create([
            'duty_name' => '(L) Grilling'
        ]);

        Duty::create([
            'duty_name' => '(L) Runner/ Side Dish'
        ]);

        Duty::create([
            'duty_name' => '(L) Cleaning'
        ]);

        Duty::create([
            'duty_name' => '(D) Grilling'
        ]);

        Duty::create([
            'duty_name' => '(D) Runner/ Side Dish'
        ]);

        Duty::create([
            'duty_name' => '(D) Cleaning'
        ]);
    }
}
