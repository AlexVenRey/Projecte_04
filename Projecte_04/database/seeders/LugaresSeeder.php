<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LugaresSeeder extends Seeder
{
    public function run()
    {
        // Insertar lugares
        DB::table('lugares')->insert([
            [
                'nombre' => 'Parc de Bellvitge',
                'descripcion' => 'Un parque amplio con zonas verdes y áreas recreativas.',
                'direccion' => 'Carrer de l’Ermita, Bellvitge',
                'latitud' => 41.348986,
                'longitud' => 2.111117,
                'icono' => 'parque.png',
                'color_marcador' => '#00FF00',
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Hospital de Bellvitge',
                'descripcion' => 'Un hospital universitario de referencia en la región.',
                'direccion' => 'Carrer de la Feixa Llarga, Bellvitge',
                'latitud' => 41.3431,
                'longitud' => 2.1060,
                'icono' => 'hospital.png',
                'color_marcador' => '#FF0000',
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Poliesportiu Sergio Manzano',
                'descripcion' => 'Instalaciones deportivas para practicar diferentes deportes.',
                'direccion' => 'Carrer de la Feixa Llarga, Bellvitge',
                'latitud' => 41.3485,
                'longitud' => 2.1065,
                'icono' => 'deportes.png',
                'color_marcador' => '#FFA500',
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}