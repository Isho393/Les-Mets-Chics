<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Admin Les Mets Chics',
            'email' => 'admin@lesmets-chics.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Create Authors
        User::create([
            'name' => 'Chef Pierre Dubois',
            'email' => 'pierre@lesmets-chics.com',
            'password' => Hash::make('password'),
            'role' => 'auteur',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Chef Marie Laurent',
            'email' => 'marie@lesmets-chics.com',
            'password' => Hash::make('password'),
            'role' => 'auteur',
            'status' => 'active',
        ]);

        // Create Explorateurs
        User::create([
            'name' => 'Jean Gourmet',
            'email' => 'jean@example.com',
            'password' => Hash::make('password'),
            'role' => 'explorateur',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Sophie Cuisine',
            'email' => 'sophie@example.com',
            'password' => Hash::make('password'),
            'role' => 'explorateur',
            'status' => 'pending', // En attente de validation
        ]);
    }
}
