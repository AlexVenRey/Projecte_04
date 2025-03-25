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
        Schema::create('gimcana_participantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gimcana_id')->constrained('gimcanas')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->timestamp('fecha_union')->useCurrent();
            $table->unique(['gimcana_id', 'usuario_id']); // Un usuario solo puede unirse una vez a una gimcana
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gimcana_participantes');
    }
};
