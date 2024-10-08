<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {

        $this->call([
            UserSeeder::class,
            PositionSeeder::class,
            SettingSeeder::class,
            DepartmentSeeder::class,
            DutySeeder::class,
            ShiftSeeder::class,
            PeriodSeeder::class,
            OutletSeeder::class,
        ]);
    }
}
