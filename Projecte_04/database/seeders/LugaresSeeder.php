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
        $lugares = [
            [
                'nombre' => 'Hospital Universitari de Bellvitge',
                'descripcion' => 'Hospital universitario de referencia',
                'latitud' => 41.344406,
                'longitud' => 2.104528,
                'color_marcador' => '#FF0000',
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Institut Joan XXIII',
                'descripcion' => 'Centro educativo de formación profesional',
                'latitud' => 41.349684,
                'longitud' => 2.107894,
                'color_marcador' => '#0000FF',
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Parc de Bellvitge',
                'descripcion' => 'Parque público con áreas verdes y zonas de recreo',
                'latitud' => 41.348142,
                'longitud' => 2.111358,
                'color_marcador' => '#00FF00',
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Estación de Metro Bellvitge',
                'descripcion' => 'Estación de la línea 1 del metro de Barcelona',
                'latitud' => 41.350718,
                'longitud' => 2.110901,
                'color_marcador' => '#FFA500',
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Centro Comercial Gran Via 2',
                'descripcion' => 'Centro comercial con tiendas, restaurantes y cine',
                'latitud' => 41.357866,
                'longitud' => 2.129336,
                'color_marcador' => '#800080',
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'GolaGol',
                'descripcion' => 'Centro deportivo especializado en fútbol sala.',
                'latitud' => 41.350491,
                'longitud' => 2.0996411,
                'color_marcador' => '#FF6347',
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Museu de L’Hospitalet',
                'descripcion' => 'Museo dedicado a la historia y cultura de L’Hospitalet.',
                'latitud' => 41.3610685,
                'longitud' => 2.0972365,
                'color_marcador' => '#FFD700',
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'La Farga',
                'descripcion' => 'Centro comercial con tiendas, restaurantes y eventos.',
                'latitud' => 41.3629593,
                'longitud' => 2.1046166,
                'color_marcador' => '#4B0082',
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Sala Salamandra',
                'descripcion' => 'Sala de conciertos y discoteca historica.',
                'latitud' => 41.359436,
                'longitud' => 2.1048567,
                'color_marcador' => '#FF4500',
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Auditori Barradas',
                'descripcion' => 'Auditorio para eventos culturales y musicales.',
                'latitud' => 41.3614793,
                'longitud' => 2.1024793,
                'color_marcador' => '#4682B4',
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Parque Can Boixeres',
                'descripcion' => 'Parque urbano con amplias áreas verdes cerca de Rambla Just Oliveres.',
                'latitud' => 41.3649528,
                'longitud' => 2.0963764,
                'color_marcador' => '#32CD32',
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Pàdel Top Club',
                'descripcion' => 'Club deportivo especializado en pádel cerca del Zoco.',
                'latitud' => 41.3579963,
                'longitud' => 2.1123531,
                'color_marcador' => '#8A2BE2',
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Frankfurt del Centre',
                'descripcion' => 'Restaurante especializado en comida rápida y frankfurts ubicado cerca de Rambla Just Oliveres.',
                'latitud' => 41.3619901,
                'longitud' => 2.1016585,
                'color_marcador' => '#FF69B4',
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Escola Canigó',
                'descripcion' => 'Centro educativo de primaria y secundaria.',
                'latitud' => 41.3620526,
                'longitud' => 2.1007642,
                'color_marcador' => '#1E90FF',
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Capitolio',
                'descripcion' => 'Discoteca Latinoamarecina en Hospitalet.',
                'latitud' => 41.3557279,
                'longitud' => 2.0958446,
                'color_marcador' => '#FF8C00',
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
        $deportes = Etiqueta::where('nombre', 'Deportes')->first()->id;
        $cultura = Etiqueta::where('nombre', 'Cultura')->first()->id;
        $restaurantes = Etiqueta::where('nombre', 'Restaurantes')->first()->id;

        // Asignar etiquetas a los lugares
        $lugar_etiqueta = [
            ['lugar_id' => 1, 'etiqueta_id' => $sanidad],
            ['lugar_id' => 2, 'etiqueta_id' => $educacion],
            ['lugar_id' => 3, 'etiqueta_id' => $parques],
            ['lugar_id' => 4, 'etiqueta_id' => $transporte],
            ['lugar_id' => 5, 'etiqueta_id' => $compras],
            ['lugar_id' => 5, 'etiqueta_id' => $ocio], // Centro comercial tiene dos etiquetas
            ['lugar_id' => 6, 'etiqueta_id' => $deportes], // GolaGol
            ['lugar_id' => 7, 'etiqueta_id' => $cultura], // Museu de L’Hospitalet
            ['lugar_id' => 8, 'etiqueta_id' => $compras], // La Farga
            ['lugar_id' => 9, 'etiqueta_id' => $ocio], // Sala Salamandra
            ['lugar_id' => 10, 'etiqueta_id' => $cultura], // Auditori Barradas
            ['lugar_id' => 11, 'etiqueta_id' => $parques], // Parque Can Boixeres
            ['lugar_id' => 12, 'etiqueta_id' => $deportes], // Pàdel Top Club
            ['lugar_id' => 13, 'etiqueta_id' => $restaurantes], // Frankfurt del Centre
            ['lugar_id' => 14, 'etiqueta_id' => $educacion], // Escola Canigó
            ['lugar_id' => 15, 'etiqueta_id' => $ocio] // Capitolio
        ];

        DB::table('lugar_etiqueta')->insert($lugar_etiqueta);
    }
}
