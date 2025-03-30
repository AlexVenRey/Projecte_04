<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosGruposTable extends Migration
{
    public function up()
    {
        Schema::create('usuarios_grupos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('grupo_id')->constrained('grupos')->onDelete('cascade');
            $table->boolean('esta_listo')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuarios_grupos');
    }
}
