<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EtiquetasSeeder extends Seeder
{
    public function run()
    {
        $etiquetas = [
            ['nombre' => 'Cultura', 'icono' => 'fa-landmark', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Naturaleza', 'icono' => 'fa-tree', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Gastronomía', 'icono' => 'fa-utensils', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Historia', 'icono' => 'fa-monument', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Arte', 'icono' => 'fa-palette', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Deporte', 'icono' => 'fa-person-running', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('etiquetas')->insert($etiquetas);
    }
}
