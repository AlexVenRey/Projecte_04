<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGimcanaLugarTable extends Migration
{
    public function up()
    {
        Schema::create('gimcana_lugar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gimcana_id')->constrained('gimcanas')->onDelete('cascade');
            $table->foreignId('lugar_id')->constrained('lugares')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gimcana_lugar');
    }
}
