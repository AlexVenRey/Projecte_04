<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PuntoControl;
use App\Models\Prueba;
use App\Models\Gimcana;
use App\Models\Lugar;
use Illuminate\Support\Facades\DB;

class PuntosControlSeeder extends Seeder
{
    public function run()
    {
        // Limpiar todas las tablas relacionadas y reiniciar los IDs
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('puntos_control')->truncate();
        DB::table('pruebas')->truncate();
        DB::statement('ALTER TABLE puntos_control AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE pruebas AUTO_INCREMENT = 1');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Verificar que existe la gimcana
        $gimcana = Gimcana::where('nombre', 'Gimcana Bellvitge')->first();
        if (!$gimcana) {
            throw new \Exception('No se encontró la gimcana Bellvitge');
        }

        // Definir los lugares y sus pruebas en el orden exacto
        $lugaresYPruebas = [
            [
                'nombre' => 'Institut Joan XXIII',
                'descripcion' => 'De que color es la puerta del instituto?',
                'respuestas_validas' => 'blanca,blanco,Blanca,Blanco'
            ],
            [
                'nombre' => 'Parc de Bellvitge',
                'descripcion' => 'Hay un gimnasio al aire libre en el parque?',
                'respuestas_validas' => 'si,Si,SI,sí,Sí,SÍ'
            ],
            [
                'nombre' => 'Hospital Universitari de Bellvitge',
                'descripcion' => 'De que color es el letrero del hospital que pone BELLVITGE',
                'respuestas_validas' => 'rojo,Rojo,ROJO,rojas,Rojas,ROJAS,roja,Roja,ROJA'
            ],
            [
                'nombre' => 'Estación de Metro Bellvitge',
                'descripcion' => 'Que linea de metro es la que corresponde a esa parada',
                'respuestas_validas' => '1,L1,l1,linea 1,línea 1,Linea 1,Línea 1'
            ]
        ];

        foreach ($lugaresYPruebas as $index => $lugarInfo) {
            // Encontrar el lugar
            $lugar = Lugar::where('nombre', $lugarInfo['nombre'])->first();
            if (!$lugar) {
                throw new \Exception("No se encontró el lugar: {$lugarInfo['nombre']}");
            }

            // Verificar que el lugar está asociado a la gimcana
            $gimcanaLugar = DB::table('gimcana_lugar')
                ->where('gimcana_id', $gimcana->id)
                ->where('lugar_id', $lugar->id)
                ->first();
            
            if (!$gimcanaLugar) {
                throw new \Exception("El lugar {$lugarInfo['nombre']} no está asociado a la gimcana");
            }

            // Crear punto de control
            $puntoControl = PuntoControl::create([
                'lugar_id' => $lugar->id,
                'pista' => "Encuentra {$lugarInfo['nombre']}"
            ]);

            // Crear la prueba con respuestas válidas
            $prueba = Prueba::create([
                'punto_control_id' => $puntoControl->id,
                'descripcion' => $lugarInfo['descripcion'],
                'respuesta' => $lugarInfo['respuestas_validas']
            ]);

            // Verificación explícita
            echo "=== Punto de Control #{$puntoControl->id} ===\n";
            echo "Lugar: {$lugarInfo['nombre']}\n";
            echo "Pregunta: {$lugarInfo['descripcion']}\n";
            echo "Respuestas válidas: {$lugarInfo['respuestas_validas']}\n";
            echo "----------------------------------------\n";

            // Verificar que todo se guardó correctamente
            $verificacion = PuntoControl::with('prueba')->find($puntoControl->id);
            if (!$verificacion || !$verificacion->prueba) {
                throw new \Exception("Error en la verificación del punto de control {$puntoControl->id}");
            }

            // Verificar que las respuestas se guardaron correctamente
            $respuestasGuardadas = explode(',', $verificacion->prueba->respuesta);
            $respuestasEsperadas = explode(',', $lugarInfo['respuestas_validas']);
            
            if (count(array_diff($respuestasGuardadas, $respuestasEsperadas)) > 0) {
                throw new \Exception("Las respuestas no coinciden para el punto de control {$puntoControl->id}");
            }

            // Log detallado
            echo "Verificación de respuestas:\n";
            echo "Guardadas: " . implode(', ', $respuestasGuardadas) . "\n";
            echo "Esperadas: " . implode(', ', $respuestasEsperadas) . "\n";
            echo "----------------------------------------\n";
        }

        // Verificación final
        $totalPuntos = PuntoControl::count();
        $totalPruebas = Prueba::count();
        echo "\nVerificación final:\n";
        echo "Total puntos de control creados: {$totalPuntos}\n";
        echo "Total pruebas creadas: {$totalPruebas}\n";
    }
} 