<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@gmail.com',
            'no_hp' => '081234567890',
            'role' => 'admin',
            'status_aktif' => true,
            'password' => Hash::make('password123'),
        ]);
    }
}
