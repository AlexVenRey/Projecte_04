<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRespuestaToPruebasTable extends Migration
{
    public function up()
    {
        Schema::table('pruebas', function (Blueprint $table) {
            $table->string('respuesta')->after('descripcion'); // Añadimos la columna respuesta después de descripción
        });
    }

    public function down()
    {
        Schema::table('pruebas', function (Blueprint $table) {
            $table->dropColumn('respuesta');
        });
    }
} 