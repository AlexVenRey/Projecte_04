<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Lugar;
use App\Models\PuntoControl;
use App\Models\Prueba;

class PuntosControlSeeder extends Seeder
{
    public function run()
    {
        $lugares = Lugar::all();
        
        foreach ($lugares as $lugar) {
            $puntoControl = PuntoControl::create([
                'lugar_id' => $lugar->id,
                'pista' => "Busca cerca de " . $lugar->nombre
            ]);

            Prueba::create([
                'punto_control_id' => $puntoControl->id,
                'descripcion' => "¿Cuál es el nombre de este lugar?",
                'respuesta' => $lugar->nombre
            ]);
        }
    }
} 