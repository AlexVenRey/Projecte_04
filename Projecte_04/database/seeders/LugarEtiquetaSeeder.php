<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Lugar;
use App\Models\Etiqueta;

class LugarEtiquetaSeeder extends Seeder
{
    public function run()
    {
        $lugares = Lugar::all();
        $etiquetas = Etiqueta::all();

        // Asignar etiquetas aleatorias a cada lugar
        foreach ($lugares as $lugar) {
            // Asignar entre 1 y 3 etiquetas aleatorias a cada lugar
            $etiquetasAleatorias = $etiquetas->random(rand(1, 3));
            
            foreach ($etiquetasAleatorias as $etiqueta) {
                DB::table('lugar_etiqueta')->insert([
                    'lugar_id' => $lugar->id,
                    'etiqueta_id' => $etiqueta->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
