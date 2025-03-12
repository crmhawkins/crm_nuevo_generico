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
        Schema::create('maquinas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->string('n_serie')->nullable();
            $table->foreignId('categoria_id')->nullable();
            $table->text('otros')->nullable();
            $table->foreignId('almacen_id')->nullable();
            $table->foreignId('salon_id')->nullable();
            $table->foreignId('local_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maquinas');
    }
};
