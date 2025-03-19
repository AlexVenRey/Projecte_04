<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GruposSeeder extends Seeder
{
    public function run()
    {
        DB::table('grupos')->insert([
            [
                'nombre' => 'Grupo A',
                'descripcion' => 'Este es el grupo A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Grupo B',
                'descripcion' => 'Este es el grupo B',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
