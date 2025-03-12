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
        Schema::create('maquina_recaudacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maquina_id');
            $table->integer('billetes_500')->nullable();
            $table->integer('billetes_200')->nullable();
            $table->integer('billetes_100')->nullable();
            $table->integer('billetes_50')->nullable();
            $table->integer('billetes_20')->nullable();
            $table->integer('billetes_10')->nullable();
            $table->integer('billetes_5')->nullable();
            $table->integer('monedas_200')->nullable();
            $table->integer('monedas_100')->nullable();
            $table->integer('monedas_50')->nullable();
            $table->integer('monedas_20')->nullable();
            $table->integer('monedas_10')->nullable();
            $table->integer('monedas_5')->nullable();
            $table->integer('monedas_2')->nullable();
            $table->integer('monedas_1')->nullable();
            $table->decimal('recaudado',8,2)->nullable();
            $table->decimal('monto',8,2)->nullable();
            $table->date('fecha_recaudacion')->nullable();
            $table->foreignId('cliente_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maquina_recaudacion');
    }
};
