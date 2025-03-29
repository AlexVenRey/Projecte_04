<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRespuestaToPruebasTable extends Migration
{
    public function up()
    {
        Schema::table('pruebas', function (Blueprint $table) {
            if (!Schema::hasColumn('pruebas', 'respuesta')) {
                $table->string('respuesta')->after('descripcion');
            }
        });
    }

    public function down()
    {
        Schema::table('pruebas', function (Blueprint $table) {
            if (Schema::hasColumn('pruebas', 'respuesta')) {
                $table->dropColumn('respuesta');
            }
        });
    }
} 