<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // services_categories: add inactive column if missing
        if (!Schema::hasColumn('services_categories', 'inactive')) {
            DB::statement('ALTER TABLE services_categories ADD COLUMN inactive TINYINT(1) NOT NULL DEFAULT 0');
        }

        // emails: add admin_user_id column if missing
        if (!Schema::hasColumn('emails', 'admin_user_id')) {
            DB::statement('ALTER TABLE emails ADD COLUMN admin_user_id BIGINT UNSIGNED NULL');
        }

        // invoice_reference_autoincrements: fix nullable columns that lack defaults
        DB::statement('ALTER TABLE invoice_reference_autoincrements MODIFY month VARCHAR(10) NULL DEFAULT NULL');
        DB::statement('ALTER TABLE invoice_reference_autoincrements MODIFY month_full VARCHAR(20) NULL DEFAULT NULL');
        DB::statement('ALTER TABLE invoice_reference_autoincrements MODIFY day INT UNSIGNED NULL DEFAULT NULL');
        DB::statement('ALTER TABLE invoice_reference_autoincrements MODIFY empresa_id BIGINT UNSIGNED NULL DEFAULT 1');
    }

    public function down(): void
    {
        // Reversing column additions only — not removing nullable fixes
        if (Schema::hasColumn('services_categories', 'inactive')) {
            DB::statement('ALTER TABLE services_categories DROP COLUMN inactive');
        }
        if (Schema::hasColumn('emails', 'admin_user_id')) {
            DB::statement('ALTER TABLE emails DROP COLUMN admin_user_id');
        }
    }
};
