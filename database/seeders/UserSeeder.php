<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'employee_id' => 'Admin123',
            'full_name' => 'admin',
            'address' => 'Test',
            'employee_type' => 'admin',
            'working_hour' => '12',     
            'email' => 'admin@admin.com',
            'password' => Hash::make('Test1234.'),
            'role' => 'admin'
        ]);
    }
}
