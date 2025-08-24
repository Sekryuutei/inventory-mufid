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
        // Akun Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@drass.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        // Akun Manager
        User::create([
            'name' => 'Manager User',
            'email' => 'manager@drass.com',
            'role' => 'manager',
            'password' => Hash::make('password'),
        ]);

        // Akun Staff
        User::create([
            'name' => 'Staff User',
            'email' => 'staff@drass.com',
            'role' => 'staff',
            'password' => Hash::make('password'),
        ]);
    }
}
