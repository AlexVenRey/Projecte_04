<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\GruposSeeder;
use Database\Seeders\LugaresSeeder;
use Database\Seeders\EtiquetasSeeder;

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
            LugaresSeeder::class,
            EtiquetasSeeder::class,
        ]);
    }
}
