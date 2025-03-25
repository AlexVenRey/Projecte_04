<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePuntosUsuariosTable extends Migration
{
    public function up()
    {
        Schema::create('puntos_usuarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('lugar_id');
            $table->timestamps();

            // Claves foráneas
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('lugar_id')->references('id')->on('lugares')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('puntos_usuarios');
    }
}
