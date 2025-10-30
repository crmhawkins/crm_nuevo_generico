<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WipeDocsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:wipe-docs {--yes : Confirmar borrado sin preguntar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Borra TODAS las Pre-facturas (budgets) y Facturas y sus conceptos asociados, y limpia PDFs generados.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (!$this->option('yes')) {
            if (!$this->confirm('Esto BORRARÁ TODAS las Pre-facturas y Facturas de la base de datos y eliminará PDFs. ¿Continuar?')) {
                $this->info('Operación cancelada.');
                return self::SUCCESS;
            }
        }

        try {
            DB::beginTransaction();

            // Desactivar checks (MySQL/MariaDB)
            try { DB::statement('SET FOREIGN_KEY_CHECKS=0'); } catch (\Throwable $e) {}

            // Limpiar tablas de facturas primero
            $this->truncateTableIfExists('invoice_concepts');
            $this->truncateTableIfExists('invoices');

            // Limpiar tablas de pre-facturas (budgets)
            $this->truncateTableIfExists('budget_concepts');
            $this->truncateTableIfExists('budgets');

            // Rehabilitar checks
            try { DB::statement('SET FOREIGN_KEY_CHECKS=1'); } catch (\Throwable $e) {}

            DB::commit();
            $this->info('Tablas de Pre-facturas y Facturas vaciadas correctamente.');

            // Eliminar PDFs/temporales relacionados (no crítico si no existen)
            $this->deleteDirSafe('public/assets/budgets');
            $this->deleteDirSafe('public/assets/temp/invoices');
            $this->deleteDirSafe('public/assets/temp');

            $this->info('Directorios de PDFs temporales eliminados.');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Error al borrar documentos: ' . $e->getMessage());
            return self::FAILURE;
        }
    }

    private function truncateTableIfExists(string $table): void
    {
        if (DB::getSchemaBuilder()->hasTable($table)) {
            DB::table($table)->truncate();
            $this->line(" - Truncada tabla: {$table}");
        }
    }

    private function deleteDirSafe(string $path): void
    {
        try {
            if (Storage::exists($path)) {
                Storage::deleteDirectory($path);
                $this->line(" - Eliminado directorio: storage/app/{$path}");
            }
        } catch (\Throwable $e) {
            $this->warn("No se pudo eliminar {$path}: " . $e->getMessage());
        }
    }
}


