<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lugar;
use App\Models\Etiqueta;

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
                'direccion' => 'Carrer de la Feixa Llarga, s/n, 08907 L\'Hospitalet de Llobregat',
                'icono' => 'hospital.png',
                'color_marcador' => '#FF0000',
                'creado_por' => 1
            ],
            [
                'nombre' => 'Institut Joan XXIII',
                'descripcion' => 'Centro educativo de formación profesional',
                'latitud' => 41.3479,
                'longitud' => 2.1045,
                'direccion' => 'Av. de la Mare de Déu de Bellvitge, 100, 08907 L\'Hospitalet de Llobregat',
                'icono' => 'school.png',
                'color_marcador' => '#0000FF',
                'creado_por' => 1
            ],
            [
                'nombre' => 'Parc de Bellvitge',
                'descripcion' => 'Parque público con áreas verdes y zonas de recreo',
                'latitud' => 41.3467,
                'longitud' => 2.1067,
                'direccion' => 'Av. de la Mare de Déu de Bellvitge, 08907 L\'Hospitalet de Llobregat',
                'icono' => 'park.png',
                'color_marcador' => '#00FF00',
                'creado_por' => 1
            ],
            [
                'nombre' => 'Estación de Metro Bellvitge',
                'descripcion' => 'Estación de la línea 1 del metro de Barcelona',
                'latitud' => 41.3611,
                'longitud' => 2.1127,
                'direccion' => 'Av. de la Mare de Déu de Bellvitge, 08907 L\'Hospitalet de Llobregat',
                'icono' => 'metro.png',
                'color_marcador' => '#FFA500',
                'creado_por' => 1
            ],
            [
                'nombre' => 'Centro Comercial Gran Via 2',
                'descripcion' => 'Centro comercial con tiendas, restaurantes y cine',
                'latitud' => 41.3589,
                'longitud' => 2.1289,
                'direccion' => 'Av. de la Granvia, 75, 08908 L\'Hospitalet de Llobregat',
                'icono' => 'shopping.png',
                'color_marcador' => '#800080',
                'creado_por' => 1
            ]
        ];

        // Crear los lugares
        foreach ($lugares as $lugar) {
            Lugar::create($lugar);
        }

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