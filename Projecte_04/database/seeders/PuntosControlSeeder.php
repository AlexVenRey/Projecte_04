<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PuntosControlSeeder extends Seeder
{
    public function run()
    {
        // Desactivar temporalmente las restricciones de clave foránea
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Limpiamos las tablas existentes
        DB::table('pruebas')->truncate();
        DB::table('puntos_control')->truncate();

        // Reactivar las restricciones
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Verificar que la gimcana existe
        $gimcanaId = DB::table('gimcanas')->where('id', 1)->first();
        if (!$gimcanaId) {
            throw new \Exception('La gimcana con ID 1 no existe. Asegúrate de crear primero la gimcana.');
        }

        // Verificar que los lugares existen en gimcana_lugar
        $lugaresGimcana = DB::table('gimcana_lugar')
            ->where('gimcana_id', 1)
            ->pluck('lugar_id')
            ->toArray();

        // Creamos los puntos de control para la gimcana con ID 1
        $puntosControl = [
            [
                'lugar_id' => 1, // Hospital Universitari de Bellvitge
                'pista' => 'Tu primera parada es un lugar donde cuidan de la salud. Es el hospital más grande de la zona y está cerca de la Granvia.',
                'prueba' => [
                    'descripcion' => '¿Cuántas plantas principales tiene el edificio del Hospital de Bellvitge?',
                    'respuesta' => '14'
                ]
            ],
            [
                'lugar_id' => 2, // Institut Joan XXIII
                'pista' => 'Dirígete al norte desde el hospital. Busca un centro educativo donde se forman futuros profesionales.',
                'prueba' => [
                    'descripcion' => '¿Qué tipo de estudios se pueden cursar en este instituto?',
                    'respuesta' => 'FP'
                ]
            ],
            [
                'lugar_id' => 3, // Parc de Bellvitge
                'pista' => 'Cerca del instituto encontrarás un espacio verde con juegos infantiles y zonas de descanso.',
                'prueba' => [
                    'descripcion' => '¿Qué animal está representado en los columpios principales del parque?',
                    'respuesta' => 'elefante'
                ]
            ],
            [
                'lugar_id' => 4, // Estación de Metro Bellvitge
                'pista' => 'Tu destino final está al norte del parque. Es una estación de la línea roja del metro.',
                'prueba' => [
                    'descripcion' => '¿Qué número de línea de metro es la que para en esta estación?',
                    'respuesta' => 'L1'
                ]
            ]
        ];

        foreach ($puntosControl as $index => $punto) {
            // Verificar que el lugar existe en la gimcana
            if (!in_array($punto['lugar_id'], $lugaresGimcana)) {
                continue;
            }

            // Insertar punto de control
            $puntoControlId = DB::table('puntos_control')->insertGetId([
                'lugar_id' => $punto['lugar_id'],
                'pista' => $punto['pista'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Insertar prueba asociada
            DB::table('pruebas')->insert([
                'punto_control_id' => $puntoControlId,
                'descripcion' => $punto['prueba']['descripcion'],
                'respuesta' => $punto['prueba']['respuesta'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
} 