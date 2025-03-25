<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('acertijos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gimcana_id')->constrained()->onDelete('cascade');
            $table->foreignId('lugar_id')->constrained('lugares')->onDelete('cascade');
            $table->string('texto_acertijo');
            $table->string('pista');
            $table->decimal('latitud_acertijo', 10, 8);
            $table->decimal('longitud_acertijo', 11, 8);
            $table->integer('orden');
            $table->timestamps();
        });

        // Actualizamos la tabla progreso_gimcana existente
        Schema::table('progreso_gimcana', function (Blueprint $table) {
            $table->foreignId('acertijo_actual_id')->nullable()->constrained('acertijos')->onDelete('cascade');
            $table->boolean('acertijo_resuelto')->default(false);
            $table->boolean('pista_revelada')->default(false);
            $table->boolean('lugar_encontrado')->default(false);
        });
    }

    public function down()
    {
        Schema::table('progreso_gimcana', function (Blueprint $table) {
            $table->dropForeign(['acertijo_actual_id']);
            $table->dropColumn(['acertijo_actual_id', 'acertijo_resuelto', 'pista_revelada', 'lugar_encontrado']);
        });
        Schema::dropIfExists('acertijos');
    }
};
