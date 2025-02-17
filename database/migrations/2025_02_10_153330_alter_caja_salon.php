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
        Schema::table('caja_salon', function (Blueprint $table) {
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('caja_salon', function (Blueprint $table) {
            $table->dropColumn('billetes_500');
            $table->dropColumn('billetes_200');
            $table->dropColumn('billetes_100');
            $table->dropColumn('billetes_50');
            $table->dropColumn('billetes_20');
            $table->dropColumn('billetes_10');
            $table->dropColumn('billetes_5');
            $table->dropColumn('monedas_200');
            $table->dropColumn('monedas_100');
            $table->dropColumn('monedas_50');
            $table->dropColumn('monedas_20');
            $table->dropColumn('monedas_10');
            $table->dropColumn('monedas_5');
            $table->dropColumn('monedas_2');
            $table->dropColumn('monedas_1');
        });
    }
};
