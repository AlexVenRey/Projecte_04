<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Etiqueta;
use App\Models\Lugar;

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
                'color_marcador' => '#800080',
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('lugares')->insert($lugares);

        // Obtener IDs de etiquetas
        $sanidad = Etiqueta::where('nombre', 'Sanidad')->first()->id;
        $educacion = Etiqueta::where('nombre', 'Educación')->first()->id;
        $parques = Etiqueta::where('nombre', 'Parques')->first()->id;
        $transporte = Etiqueta::where('nombre', 'Transporte')->first()->id;
        $compras = Etiqueta::where('nombre', 'Compras')->first()->id;
        $ocio = Etiqueta::where('nombre', 'Ocio')->first()->id;

        // Asignar etiquetas a los lugares
        $lugar_etiqueta = [
            ['lugar_id' => 1, 'etiqueta_id' => $sanidad],
            ['lugar_id' => 2, 'etiqueta_id' => $educacion],
            ['lugar_id' => 3, 'etiqueta_id' => $parques],
            ['lugar_id' => 4, 'etiqueta_id' => $transporte],
            ['lugar_id' => 5, 'etiqueta_id' => $compras],
            ['lugar_id' => 5, 'etiqueta_id' => $ocio] // Centro comercial tiene dos etiquetas
        ];

        DB::table('lugar_etiqueta')->insert($lugar_etiqueta);
    }
}