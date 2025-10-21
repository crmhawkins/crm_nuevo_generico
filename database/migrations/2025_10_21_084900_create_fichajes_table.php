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
        Schema::create('fichajes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('fecha');
            $table->time('hora_entrada')->nullable();
            $table->time('hora_salida')->nullable();
            $table->time('hora_pausa_inicio')->nullable();
            $table->time('hora_pausa_fin')->nullable();
            $table->integer('tiempo_trabajado')->default(0); // en minutos
            $table->integer('tiempo_pausa')->default(0); // en minutos
            $table->enum('estado', ['entrada', 'pausa', 'trabajando', 'salida'])->default('entrada');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('admin_user')->onDelete('cascade');
            $table->index(['user_id', 'fecha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fichajes');
    }
};
