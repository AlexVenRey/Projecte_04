<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EtiquetasSeeder extends Seeder
{
    public function run()
    {
        DB::table('etiquetas')->insert([
            ['nombre' => 'Cultura', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Naturaleza', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Gastronomía', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Deportes', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Museo', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Parques', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Restaurantes', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Otros', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Centro Médico', 'created_at' => now(), 'updated_at' => now()], // Nueva etiqueta
        ]);
    }
}
