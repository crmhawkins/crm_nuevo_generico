<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        // budget_status: ensure all 7 statuses exist
        $statuses = [
            ['id' => 1, 'name' => 'Pendiente de confirmar'],
            ['id' => 2, 'name' => 'Confirmado'],
            ['id' => 3, 'name' => 'Aceptado'],
            ['id' => 4, 'name' => 'Cancelado'],
            ['id' => 5, 'name' => 'Finalizado'],
            ['id' => 6, 'name' => 'Facturado'],
            ['id' => 7, 'name' => 'Facturado parcialmente'],
        ];
        foreach ($statuses as $s) {
            DB::table('budget_status')->insertOrIgnore(array_merge($s, ['created_at' => $now, 'updated_at' => $now]));
        }

        // invoice_status: ensure all 5 statuses exist
        $invoiceStatuses = [
            ['id' => 1, 'name' => 'Pendiente'],
            ['id' => 2, 'name' => 'No cobrada'],
            ['id' => 3, 'name' => 'Cobrada'],
            ['id' => 4, 'name' => 'Cobrada parcialmente'],
            ['id' => 5, 'name' => 'Cancelada'],
        ];
        foreach ($invoiceStatuses as $s) {
            DB::table('invoice_status')->insertOrIgnore(array_merge($s, ['created_at' => $now, 'updated_at' => $now]));
        }

        // payment_method: seed basic methods
        $paymentMethods = [
            ['id' => 1, 'name' => 'Transferencia bancaria'],
            ['id' => 2, 'name' => 'Tarjeta de crédito'],
            ['id' => 3, 'name' => 'Efectivo'],
            ['id' => 4, 'name' => 'Domiciliación bancaria'],
            ['id' => 5, 'name' => 'Cheque'],
            ['id' => 9, 'name' => 'Transferencia'],
        ];
        foreach ($paymentMethods as $pm) {
            DB::table('payment_method')->insertOrIgnore(array_merge($pm, ['created_at' => $now, 'updated_at' => $now]));
        }

        // company_details: insert default row if empty
        if (DB::table('company_details')->count() === 0) {
            DB::table('company_details')->insert([
                'id'           => 1,
                'company_name' => config('app.name', 'Mi Empresa'),
                'email'        => config('mail.from.address', 'admin@example.com'),
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }

    public function down(): void
    {
        // No-op: seed data removal could break FK constraints
    }
};
