<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gimcanas', function (Blueprint $table) {
            $table->id(); // Crea una columna id auto incremental
            $table->string('nombre'); // Crea una columna para el nombre de la gimcana
            $table->text('descripcion'); // Crea una columna para la descripción de la gimcana
            $table->foreignId('creado_por')->constrained('usuarios')->onDelete('cascade'); // Creador de la gimcana (usuarios)
            $table->timestamps(); // Crea las columnas created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gimcanas'); // Elimina la tabla si se revierte la migración
    }
};
