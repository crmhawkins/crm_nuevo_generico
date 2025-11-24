<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Asegurar tipos numéricos. Usamos change() para convertir desde string si existiese.
            if (Schema::hasColumn('invoices', 'retencion')) {
                $table->decimal('retencion', 12, 2)->nullable()->default(0)->change();
            }
            if (Schema::hasColumn('invoices', 'retencion_percentage')) {
                $table->decimal('retencion_percentage', 5, 2)->nullable()->default(0)->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Volver a string como estaba en migración anterior
            if (Schema::hasColumn('invoices', 'retencion')) {
                $table->string('retencion')->nullable()->change();
            }
            if (Schema::hasColumn('invoices', 'retencion_percentage')) {
                $table->string('retencion_percentage')->nullable()->change();
            }
        });
    }
};



