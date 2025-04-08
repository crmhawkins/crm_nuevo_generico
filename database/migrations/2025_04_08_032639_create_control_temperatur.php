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
        Schema::create('control_temperatura', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->float('temperatura_actual', 5, 2);
            $table->float('temperatura_maxima', 5, 2);
            $table->float('temperatura_minima', 5, 2);
            $table->date('fecha');
            $table->time('hora');
            $table->text('observaciones')->nullable();
            $table->integer('admin_user_id')->nullable();
            $table->integer('salon_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_temperatura');
    }
};
