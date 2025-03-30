<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Lugar;
use App\Models\Gimcana;

class GimcanasSeeder extends Seeder
{
    public function run()
    {
        // Limpiar y reiniciar
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('gimcanas')->truncate();
        DB::table('gimcana_lugar')->truncate();
        DB::statement('ALTER TABLE gimcanas AUTO_INCREMENT = 1');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Obtener el primer admin
        $admin = DB::table('usuarios')->where('rol', 'admin')->first();
        if (!$admin) {
            throw new \Exception('No se encontró ningún usuario administrador');
        }

        // Crear la gimcana
        $gimcana = Gimcana::create([
            'nombre' => 'Gimcana Bellvitge',
            'descripcion' => 'Recorrido por los lugares emblemáticos de Bellvitge',
            'creado_por' => $admin->id,
            'estado' => 'en_progreso',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Orden específico de los lugares
        $nombresLugares = [
            'Institut Joan XXIII',
            'Parc de Bellvitge',
            'Hospital Universitari de Bellvitge',
            'Estación de Metro Bellvitge'
        ];

        // Asociar lugares en el orden correcto
        foreach ($nombresLugares as $nombreLugar) {
            $lugar = Lugar::where('nombre', $nombreLugar)->first();
            if (!$lugar) {
                throw new \Exception("No se encontró el lugar: {$nombreLugar}");
            }

            DB::table('gimcana_lugar')->insert([
                'gimcana_id' => $gimcana->id,
                'lugar_id' => $lugar->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Verificación final
        $lugaresAsociados = DB::table('gimcana_lugar')
            ->where('gimcana_id', $gimcana->id)
            ->count();

        echo "Gimcana creada con ID: {$gimcana->id}\n";
        echo "Lugares asociados: {$lugaresAsociados}\n";
    }
} 