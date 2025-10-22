<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fichaje_pausas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fichaje_id');
            $table->time('inicio');
            $table->time('fin')->nullable();
            $table->timestamps();

            $table->foreign('fichaje_id')->references('id')->on('fichajes')->onDelete('cascade');
            $table->index(['fichaje_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fichaje_pausas');
    }
};


