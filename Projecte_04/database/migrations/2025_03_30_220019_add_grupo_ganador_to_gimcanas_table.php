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
        Schema::table('gimcanas', function (Blueprint $table) {
            $table->foreignId('grupo_ganador_id')->nullable()->constrained('grupos')->onDelete('set null');
            $table->timestamp('fecha_finalizacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gimcanas', function (Blueprint $table) {
            $table->dropForeign(['grupo_ganador_id']);
            $table->dropColumn(['grupo_ganador_id', 'fecha_finalizacion']);
        });
    }
};
