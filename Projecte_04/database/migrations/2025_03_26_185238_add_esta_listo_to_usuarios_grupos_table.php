<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Añade la columna esta_listo a la tabla usuarios_grupos para controlar
     * el estado de preparación de los usuarios en una gimcana
     */
    public function up()
    {
        // Primero verificamos si la columna no existe ya
        if (!Schema::hasColumn('usuarios_grupos', 'esta_listo')) {
            Schema::table('usuarios_grupos', function (Blueprint $table) {
                // Añadimos la columna después de grupo_id para mantener un orden lógico
                $table->boolean('esta_listo')
                      ->default(false)
                      ->after('grupo_id')
                      ->comment('Indica si el usuario está listo para comenzar la gimcana');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Verificamos si la columna existe antes de intentar eliminarla
        if (Schema::hasColumn('usuarios_grupos', 'esta_listo')) {
            Schema::table('usuarios_grupos', function (Blueprint $table) {
                $table->dropColumn('esta_listo');
            });
        }
    }
};