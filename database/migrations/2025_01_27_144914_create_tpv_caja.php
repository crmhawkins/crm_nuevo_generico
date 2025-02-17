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
        Schema::create('tpv_caja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salon_id')->nullable();
            $table->float('apertura', 8, 2);
            $table->float('cierre', 8, 2)->nullable();
            $table->float('previsto', 8, 2)->nullable();
            $table->float('diferencia', 8, 2)->nullable();
            $table->float('cambio', 8, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tpv_caja');
    }
};
