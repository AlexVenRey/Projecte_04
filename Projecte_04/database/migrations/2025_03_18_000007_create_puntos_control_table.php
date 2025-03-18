<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePuntosControlTable extends Migration
{
    public function up()
    {
        Schema::create('puntos_control', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lugar_id')->constrained('lugares')->onDelete('cascade');
            $table->text('pista')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('puntos_control');
    }
}
