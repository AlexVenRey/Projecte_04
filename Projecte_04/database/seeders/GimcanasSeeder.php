<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GimcanasSeeder extends Seeder
{
    public function run()
    {
        // Desactivar temporalmente las restricciones de clave foránea
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Limpiar tablas relacionadas
        DB::table('gimcanas')->truncate();
        DB::table('gimcana_lugar')->truncate();
        DB::table('gimcana_grupo')->truncate();

        // Reactivar las restricciones
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Primero verificamos que existan los datos necesarios
        $admin = DB::table('usuarios')->where('id', 2)->first();
        if (!$admin) {
            throw new \Exception('El usuario administrador (ID: 2) no existe.');
        }

        // Verificar que los lugares existen
        $lugaresExistentes = DB::table('lugares')
            ->whereIn('id', [1, 2, 3, 4])
            ->count();
        if ($lugaresExistentes !== 4) {
            throw new \Exception('No se encontraron todos los lugares necesarios.');
        }

        // Verificar que el grupo existe
        $grupo = DB::table('grupos')->where('id', 3)->first();
        if (!$grupo) {
            // Si el grupo no existe, lo creamos
            DB::table('grupos')->insert([
                'id' => 3,
                'nombre' => 'qweQWE123',
                'descripcion' => 'Grupo para la gimcana 1',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Crear la gimcana con los datos de la BD actual
        $gimcanaId = DB::table('gimcanas')->insertGetId([
            'nombre' => '3546',
            'descripcion' => '3546',
            'creado_por' => 2,
            'estado' => 'en_progreso',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Asociar los lugares a la gimcana
        $lugares = [1, 2, 3, 4];
        foreach ($lugares as $lugarId) {
            DB::table('gimcana_lugar')->insert([
                'gimcana_id' => $gimcanaId,
                'lugar_id' => $lugarId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Asociar el grupo a la gimcana
        DB::table('gimcana_grupo')->insert([
            'gimcana_id' => $gimcanaId,
            'grupo_id' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Asociar usuario al grupo si no está asociado
        if (!DB::table('usuarios_grupos')->where([
            'usuario_id' => 7,
            'grupo_id' => 3
        ])->exists()) {
            DB::table('usuarios_grupos')->insert([
                'usuario_id' => 7,
                'grupo_id' => 3,
                'esta_listo' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
} 