<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePruebasTable extends Migration
{
    public function up()
    {
        Schema::create('pruebas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('punto_control_id')->constrained('puntos_control')->onDelete('cascade');
            $table->text('descripcion');
            $table->string('respuesta');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pruebas');
    }
}
