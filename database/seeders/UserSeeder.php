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
            'full_name' => 'Admin',
            'nickname' => 'Admin',
            'ic_number' => '123',
            'address' => 'Test123',
            'email' => 'admin@admin.com',
            'working_hour' => '12',
            'employee_type' => 'admin',
            'password' => Hash::make('Test1234.'),
            'role' => 'admin'
        ]);
    }
}
