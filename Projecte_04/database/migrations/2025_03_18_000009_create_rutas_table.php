<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRutasTable extends Migration
{
    public function up()
    {
        Schema::create('rutas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained()->onDelete('cascade');
            $table->geometry('origen');  // Cambiado a 'geometry' en lugar de 'point'
            $table->geometry('destino');  // Cambiado a 'geometry' en lugar de 'point'
            $table->integer('tiempo_estimado');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rutas');
    }
}
