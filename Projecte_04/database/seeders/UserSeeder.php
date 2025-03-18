<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Administradores
        $admins = [
            [
                'nombre' => 'Alejandro González',
                'email' => 'alejandro.gonzalez@admin.com',
                'password' => hash('sha256', 'password123'),
                'rol' => 'admin',
            ],
            [
                'nombre' => 'Sergi Masi',
                'email' => 'sergi.masi@admin.com',
                'password' => hash('sha256', 'password123'),
                'rol' => 'admin',
            ],
            [
                'nombre' => 'Adrián Vazquez',
                'email' => 'adrian.vazquez@admin.com',
                'password' => hash('sha256', 'password123'),
                'rol' => 'admin',
            ],
            [
                'nombre' => 'Àlex Ventura',
                'email' => 'alex.ventura@admin.com',
                'password' => hash('sha256', 'password123'),
                'rol' => 'admin',
            ],
        ];

        foreach ($admins as $admin) {
            User::create($admin);
        }

        // Usuarios normales
        $usuarios = [
            [
                'nombre' => 'María García',
                'email' => 'maria.garcia@example.com',
                'password' => hash('sha256', 'password123'),
                'rol' => 'usuario',
            ],
            [
                'nombre' => 'Juan Rodríguez',
                'email' => 'juan.rodriguez@example.com',
                'password' => hash('sha256', 'password123'),
                'rol' => 'usuario',
            ],
            [
                'nombre' => 'Laura Martínez',
                'email' => 'laura.martinez@example.com',
                'password' => hash('sha256', 'password123'),
                'rol' => 'usuario',
            ],
            [
                'nombre' => 'Carlos López',
                'email' => 'carlos.lopez@example.com',
                'password' => hash('sha256', 'password123'),
                'rol' => 'usuario',
            ],
        ];

        foreach ($usuarios as $usuario) {
            User::create($usuario);
        }
    }
}
