<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@site.com',
            'password' => Hash::make('123456'),
            'tipo' => 'admin',
        ]);

        // FORMADOR
        User::create([
            'name' => 'Formador Principal',
            'email' => 'formador@site.com',
            'password' => Hash::make('123456'),
            'tipo' => 'formador',
        ]);

        // ESTUDANTE
        User::create([
            'name' => 'Estudante Teste',
            'email' => 'estudante@site.com',
            'password' => Hash::make('123456'),
            'tipo' => 'estudante',
        ]);
    }
}