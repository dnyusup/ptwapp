<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Administrator
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@permittowork.com',
            'password' => Hash::make('admin123'),
            'role' => 'administrator',
            'department' => 'IT',
            'phone' => '+62812345678',
        ]);

        // Contractor
        User::create([
            'name' => 'John Contractor',
            'email' => 'contractor@permittowork.com',
            'password' => Hash::make('contractor123'),
            'role' => 'contractor',
            'department' => 'Construction',
            'phone' => '+62812345679',
        ]);

        // Bekaert
        User::create([
            'name' => 'Jane Bekaert',
            'email' => 'bekaert@permittowork.com',
            'password' => Hash::make('bekaert123'),
            'role' => 'bekaert',
            'department' => 'Safety',
            'phone' => '+62812345680',
        ]);
    }
}
