<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PuntoControl;
use App\Models\Prueba;
use App\Models\Gimcana;

class PuntosControlSeeder extends Seeder
{
    public function run()
    {
        // Obtener la gimcana activa
        $gimcana = Gimcana::where('estado', 'en_progreso')->first();
        
        if ($gimcana) {
            // Crear puntos de control para cada lugar de la gimcana
            $gimcana->lugares()->each(function ($lugar) {
                // Verificar si ya existe un punto de control para este lugar
                $puntoControl = PuntoControl::firstOrCreate(
                    ['lugar_id' => $lugar->id],
                    ['pista' => "Encuentra {$lugar->nombre}"]
                );

                // Verificar si ya existe una prueba para este punto de control
                Prueba::firstOrCreate(
                    ['punto_control_id' => $puntoControl->id],
                    [
                        'descripcion' => "¿Cuál es el código que ves en {$lugar->nombre}?",
                        'respuesta' => '123'
                    ]
                );
            });
        }
    }
} 