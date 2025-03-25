<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLugaresTable extends Migration
{
    public function up()
    {
        Schema::create('lugares', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->decimal('latitud', 10, 8);
            $table->decimal('longitud', 10, 8);
            $table->text('descripcion');
            $table->string('color_marcador')->default('#3388ff');
            
            // Relación explícita (recomendado)
            $table->unsignedBigInteger('creado_por');
            $table->foreign('creado_por')
                  ->references('id')
                  ->on('usuarios')
                  ->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lugares');
    }
}