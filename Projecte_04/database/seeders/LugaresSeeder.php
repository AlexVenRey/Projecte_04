<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LugaresSeeder extends Seeder
{
    public function run()
    {
        DB::table('lugares')->insert([
            [
                'nombre' => 'Museo de Arte',
                'descripcion' => 'Un museo con una gran colección de arte moderno.',
                'direccion' => 'Calle Falsa 123',
                'latitud' => 40.416775,
                'longitud' => -3.703790,
                'icono' => 'museum.png',
                'color_marcador' => '#FF0000',
                'creado_por' => 1, // ID del usuario que lo creó
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Parque Central',
                'descripcion' => 'Un parque grande en el centro de la ciudad.',
                'direccion' => 'Avenida Siempre Viva 742',
                'latitud' => 40.417775,
                'longitud' => -3.703790,
                'icono' => 'park.png',
                'color_marcador' => '#00FF00',
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
