<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLugaresEtiquetasTable extends Migration
{
    public function up()
    {
        Schema::create('lugares_etiquetas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lugar_id')->constrained('lugares')->onDelete('cascade');
            $table->foreignId('etiqueta_id')->constrained('etiquetas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lugares_etiquetas');
    }
}
