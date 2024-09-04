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
        Schema::create('todo_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('todo_id')->constrained('to-do')->onDelete('cascade');
            $table->foreignId('admin_user_id')->constrained('admin_user')->onDelete('cascade');
            $table->boolean('completada')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todo_users');
    }
};