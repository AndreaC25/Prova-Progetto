<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Mario Rossi',
            'email' => 'mario.rossi@fumettisti.it',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // Test users
        User::create([
            'name' => 'Luca Bianchi',
            'email' => 'luca.bianchi@email.it',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Giulia Verdi',
            'email' => 'giulia.verdi@email.it',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Marco Neri',
            'email' => 'marco.neri@email.it',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
    }
}
