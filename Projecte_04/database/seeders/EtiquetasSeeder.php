<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Etiqueta;

class EtiquetasSeeder extends Seeder
{
    public function run()
    {
        $etiquetas = [
            [
                'nombre' => 'Cultura',
                'icono' => 'fa-landmark', // Icono de museo/cultura
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Educación',
                'icono' => 'fa-graduation-cap', // Icono de educación
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Parques',
                'icono' => 'fa-tree', // Icono de parque
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Transporte',
                'icono' => 'fa-train', // Icono de transporte
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Compras',
                'icono' => 'fa-shopping-cart', // Icono de compras
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Ocio',
                'icono' => 'fa-ticket-alt', // Icono de ocio
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Sanidad',
                'icono' => 'fa-hospital', // Icono de hospital
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Deportes',
                'icono' => 'fa-futbol', // Icono de deportes
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Restaurantes',
                'icono' => 'fa-utensils', // Icono de restaurantes
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($etiquetas as $etiqueta) {
            Etiqueta::create($etiqueta);
        }
    }
}
