<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Andi', 'email' => 'andi@example.com', 'password' => Hash::make('password')],
            ['name' => 'Budi', 'email' => 'budi@example.com', 'password' => Hash::make('password')],
            ['name' => 'Siti', 'email' => 'siti@example.com', 'password' => Hash::make('password')],
        ];
        foreach ($users as $u) {
            User::updateOrCreate(['email' => $u['email']], $u);
        }
    }
}