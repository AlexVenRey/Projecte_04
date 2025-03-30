<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\GruposSeeder;
use Database\Seeders\EtiquetasSeeder;
use Database\Seeders\LugaresSeeder;
use Database\Seeders\LugarEtiquetaSeeder;
use Database\Seeders\PuntosControlSeeder;
use Database\Seeders\GimcanasSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,            // 1. Primero usuarios
            EtiquetasSeeder::class,       // 2. Luego etiquetas
            LugaresSeeder::class,         // 3. Luego lugares
            LugarEtiquetaSeeder::class,   // 4. Asociar etiquetas a lugares
            GruposSeeder::class,          // 5. Crear grupos
            GimcanasSeeder::class,        // 6. Crear gimcana y asociar lugares
            PuntosControlSeeder::class,   // 7. Finalmente los puntos de control
        ]);
    }
}
