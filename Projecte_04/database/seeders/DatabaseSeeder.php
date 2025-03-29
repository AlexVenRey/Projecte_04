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
            UserSeeder::class,
            GruposSeeder::class,
            EtiquetasSeeder::class,
            LugaresSeeder::class,
            LugarEtiquetaSeeder::class,
            GimcanasSeeder::class,
            PuntosControlSeeder::class,
        ]);
    }
}
