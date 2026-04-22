<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        if (!User::where('username', 'haikal')->exists()) {
            User::create([
                'name' => 'Haikal Admin',
                'username' => 'haikal',
                'email' => 'haikal@admin.com',
                'password' => Hash::make('12345'),
                'role' => 'admin',
            ]);
        }

        if (!User::where('username', 'userbiasa')->exists()) {
            User::create([
                'name' => 'User Biasa',
                'username' => 'userbiasa',
                'email' => 'user@example.com',
                'password' => Hash::make('Password123'),
                'role' => 'user',
            ]);
        }
        
        if (!User::where('username', 'budi123')->exists()) {
            User::create([
                'name' => 'Budi Santoso',
                'username' => 'budi123',
                'email' => 'budi@example.com',
                'password' => Hash::make('Budi12345'),
                'role' => 'user',
            ]);
        }
    }
}