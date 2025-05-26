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
        Schema::create('oca', function (Blueprint $table) {
            $table->id();
            $table->date('alta')->nullable();
            $table->date('caducidad')->nullable();
            $table->string('descripcion')->nullable();
            $table->integer('alerta_id')->nullable();
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
        Schema::dropIfExists('oca');
    }
};
