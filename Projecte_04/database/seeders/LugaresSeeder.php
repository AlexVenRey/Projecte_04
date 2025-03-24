<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lugar;
use App\Models\Etiqueta;
use Illuminate\Support\Facades\DB;

class LugaresSeeder extends Seeder
{
    public function run()
    {
        // Crear algunos lugares cerca de Joan XXIII (Bellvitge)
        $lugares = [
            [
                'nombre' => 'Hospital Universitari de Bellvitge',
                'descripcion' => 'Hospital universitario de referencia',
                'latitud' => 41.3442,
                'longitud' => 2.1019,
                'icono' => 'fa-hospital',
                'color_marcador' => '#FF0000',
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Institut Joan XXIII',
                'descripcion' => 'Centro educativo de formación profesional',
                'latitud' => 41.3479,
                'longitud' => 2.1045,
                'icono' => 'fa-school',
                'color_marcador' => '#0000FF',
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Parc de Bellvitge',
                'descripcion' => 'Parque público con áreas verdes y zonas de recreo',
                'latitud' => 41.3467,
                'longitud' => 2.1067,
                'icono' => 'fa-tree',
                'color_marcador' => '#00FF00',
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Estación de Metro Bellvitge',
                'descripcion' => 'Estación de la línea 1 del metro de Barcelona',
                'latitud' => 41.3611,
                'longitud' => 2.1127,
                'icono' => 'fa-subway',
                'color_marcador' => '#FFA500',
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Centro Comercial Gran Via 2',
                'descripcion' => 'Centro comercial con tiendas, restaurantes y cine',
                'latitud' => 41.3589,
                'longitud' => 2.1289,
                'icono' => 'fa-shopping-cart',
                'color_marcador' => '#800080',
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('lugares')->insert($lugares);

        // Crear algunas etiquetas
        $etiquetas = [
            ['nombre' => 'Sanidad'],
            ['nombre' => 'Educación'],
            ['nombre' => 'Ocio'],
            ['nombre' => 'Transporte'],
            ['nombre' => 'Comercio']
        ];

        foreach ($etiquetas as $etiqueta) {
            Etiqueta::create($etiqueta);
        }

        // Asignar etiquetas a lugares
        $hospital = Lugar::where('nombre', 'Hospital Universitari de Bellvitge')->first();
        $hospital->etiquetas()->attach(Etiqueta::where('nombre', 'Sanidad')->first());

        $instituto = Lugar::where('nombre', 'Institut Joan XXIII')->first();
        $instituto->etiquetas()->attach(Etiqueta::where('nombre', 'Educación')->first());

        $parque = Lugar::where('nombre', 'Parc de Bellvitge')->first();
        $parque->etiquetas()->attach(Etiqueta::where('nombre', 'Ocio')->first());

        $metro = Lugar::where('nombre', 'Estación de Metro Bellvitge')->first();
        $metro->etiquetas()->attach(Etiqueta::where('nombre', 'Transporte')->first());

        $centro = Lugar::where('nombre', 'Centro Comercial Gran Via 2')->first();
        $centro->etiquetas()->attach([
            Etiqueta::where('nombre', 'Comercio')->first()->id,
            Etiqueta::where('nombre', 'Ocio')->first()->id
        ]);
    }
}
