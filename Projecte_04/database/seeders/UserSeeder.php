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
                'email' => 'alejandro.gonzalez@admin.com',
                'password' => Hash::make('password123'), // Usar Hash::make en lugar de hash('sha256', ...)
                'rol' => 'admin',
            ],
            [
                'nombre' => 'Sergi Masip',
                'email' => 'sergi.masip@admin.com',
                'password' => Hash::make('password123'),
                'rol' => 'admin',
            ],
            [
                'nombre' => 'Adrián Vázquez',
                'email' => 'adrian.vazquez@admin.com',
                'password' => Hash::make('password123'),
                'rol' => 'admin',
            ],
            [
                'nombre' => 'Àlex Ventura',
                'email' => 'alex.ventura@admin.com',
                'password' => Hash::make('password123'),
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
                'password' => Hash::make('password123'),
                'rol' => 'usuario',
            ],
            [
                'nombre' => 'Juan Rodríguez',
                'email' => 'juan.rodriguez@example.com',
                'password' => Hash::make('password123'),
                'rol' => 'usuario',
            ],
            [
                'nombre' => 'Laura Martínez',
                'email' => 'laura.martinez@example.com',
                'password' => Hash::make('password123'),
                'rol' => 'usuario',
            ],
            [
                'nombre' => 'Carlos López',
                'email' => 'carlos.lopez@example.com',
                'password' => Hash::make('password123'),
                'rol' => 'usuario',
            ],
        ];

        foreach ($usuarios as $usuario) {
            User::create($usuario);
        }
    }
}

?>