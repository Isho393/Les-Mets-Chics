<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    public function run()
    {
        // Admin
        User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin Test',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active'
            ]
        );

        // Auteur
        User::firstOrCreate(
            ['email' => 'auteur@test.com'],
            [
                'name' => 'Chef Auteur',
                'password' => Hash::make('password'),
                'role' => 'auteur',
                'status' => 'active'
            ]
        );

        // Utilisateur actif
        User::firstOrCreate(
            ['email' => 'user@test.com'],
            [
                'name' => 'Utilisateur Test',
                'password' => Hash::make('password'),
                'role' => 'explorateur',
                'status' => 'active'
            ]
        );

        // Utilisateur en attente
        User::firstOrCreate(
            ['email' => 'pending@test.com'],
            [
                'name' => 'En Attente',
                'password' => Hash::make('password'),
                'role' => 'explorateur',
                'status' => 'pending'
            ]
        );
    }
}