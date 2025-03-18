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
            $table->string('nombre', 150);
            $table->text('descripcion')->nullable();
            $table->string('direccion', 255)->nullable();
            $table->geometry('coordenadas'); // Cambiado a 'geometry' en lugar de 'point'
            $table->string('icono', 100)->nullable();
            $table->string('color_marcador', 7)->nullable();
            $table->foreignId('creado_por')->nullable()->constrained('usuarios')->onDelete('set null'); // Hacer que sea nullable
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lugares');
    }
}
