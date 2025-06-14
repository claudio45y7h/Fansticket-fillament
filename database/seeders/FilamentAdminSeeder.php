<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FilamentAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // Cambia esto por tu contraseña deseada
            'remember_token' => Str::random(10),
            'is_admin' => true, // Si tienes este campo
        ]);
    }
}
