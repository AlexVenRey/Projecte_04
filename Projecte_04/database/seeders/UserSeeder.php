<?php
namespace Database\Seeders;

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
        // Administradores
        $admins = [
            [
                'nombre' => 'Alejandro González',
                'email' => 'alejandro@admin.com',
                'password' => Hash::make('qweQWE123'),
                'rol' => 'admin',
            ],
            [
                'nombre' => 'Sergi Masip',
                'email' => 'sergi@admin.com',
                'password' => Hash::make('qweQWE123'),
                'rol' => 'admin',
            ],
            [
                'nombre' => 'Adrián Vazquez',
                'email' => 'adrian@admin.com',
                'password' => Hash::make('qweQWE123'),
                'rol' => 'admin',
            ],
            [
                'nombre' => 'Àlex Ventura',
                'email' => 'alex@admin.com',
                'password' => Hash::make('qweQWE123'),
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
                'email' => 'maria@example.com',
                'password' => Hash::make('qweQWE123'),
                'rol' => 'usuario',
            ],
            [
                'nombre' => 'Juan Rodríguez',
                'email' => 'juan@example.com',
                'password' => Hash::make('qweQWE123'),
                'rol' => 'usuario',
            ],
            [
                'nombre' => 'Laura Martínez',
                'email' => 'laura@example.com',
                'password' => Hash::make('qweQWE123'),
                'rol' => 'usuario',
            ],
            [
                'nombre' => 'Carlos López',
                'email' => 'carlos@example.com',
                'password' => Hash::make('qweQWE123'),
                'rol' => 'usuario',
            ],
        ];

        foreach ($usuarios as $usuario) {
            User::create($usuario);
        }
    }
}
