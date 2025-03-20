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
                'nombre' => 'Hesperia',
                'descripcion' => 'Hotel Hesperia en bellvitge.',
                'direccion' => 'Calle Falsa 123',
                'latitud' => 41.34582,
                'longitud' => 2.1082015,
                'icono' => 'museum.png',
                'color_marcador' => '#FF0000',
                'creado_por' => 1, // ID del usuario que lo creó
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Poliesportiu Sergio Manzano',
                'descripcion' => 'Poliesportiu de Bellvitge.',
                'direccion' => 'Av. Mare de Déu de Bellvitge, 7, 08907',
                'latitud' => 41.348334,
                'longitud' => 2.1053894,
                'icono' => 'park.png',
                'color_marcador' => '#00FF00',
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Ermita de Santa Maria de Bellvitge',
                'descripcion' => 'Ermita de Bellvitge',
                'direccion' => 'Av. Mare de Déu de Bellvitge, 11, 08907',
                'latitud' => 41.3474091,
                'longitud' => 2.1095477,
                'icono' => 'park.png',
                'color_marcador' => '#00FF00',
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
