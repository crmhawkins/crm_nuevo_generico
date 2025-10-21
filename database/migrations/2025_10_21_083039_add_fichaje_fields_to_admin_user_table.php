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
        Schema::table('admin_user', function (Blueprint $table) {
            $table->string('pin', 4)->nullable()->after('password');
            $table->boolean('pin_activo')->default(false)->after('pin');
            $table->boolean('password_activa')->default(true)->after('pin_activo');
            $table->timestamp('ultimo_acceso')->nullable()->after('password_activa');
            $table->enum('metodo_login', ['pin', 'password'])->default('password')->after('ultimo_acceso');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_user', function (Blueprint $table) {
            $table->dropColumn(['pin', 'pin_activo', 'password_activa', 'ultimo_acceso', 'metodo_login']);
        });
    }
};
