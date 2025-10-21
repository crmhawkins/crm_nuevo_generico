<?php

namespace App\Console\Commands;

use App\Models\Users\User;
use Illuminate\Console\Command;

class ConfigurarPINs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fichaje:configurar-pins {--regenerar : Regenerar PINs existentes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configurar PINs para usuarios del sistema de fichaje';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔐 Configurando PINs para el sistema de fichaje...');
        $this->newLine();

        $regenerar = $this->option('regenerar');
        
        if ($regenerar) {
            $this->warn('⚠️  Regenerando todos los PINs existentes...');
        }

        $users = User::where('inactive', 0)->get();
        
        if ($users->isEmpty()) {
            $this->error('❌ No se encontraron usuarios activos.');
            return;
        }

        $this->info("👥 Encontrados {$users->count()} usuarios activos");
        $this->newLine();

        $tabla = [];
        $contador = 0;

        foreach ($users as $user) {
            // Si no regenerar y ya tiene PIN, saltar
            if (!$regenerar && $user->pin && $user->pin_activo) {
                $tabla[] = [
                    'ID' => $user->id,
                    'Nombre' => $user->name . ' ' . $user->surname,
                    'Email' => $user->email,
                    'PIN' => $user->pin,
                    'Estado' => '✅ Ya configurado'
                ];
                continue;
            }

            // Generar PIN único
            do {
                $pin = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            } while (User::where('pin', $pin)->where('id', '!=', $user->id)->exists());

            $user->update([
                'pin' => $pin,
                'pin_activo' => true,
                'metodo_login' => 'pin'
            ]);

            $tabla[] = [
                'ID' => $user->id,
                'Nombre' => $user->name . ' ' . $user->surname,
                'Email' => $user->email,
                'PIN' => $pin,
                'Estado' => $regenerar ? '🔄 Regenerado' : '🆕 Nuevo'
            ];

            $contador++;
        }

        // Mostrar tabla de resultados
        $this->table(
            ['ID', 'Nombre', 'Email', 'PIN', 'Estado'],
            $tabla
        );

        $this->newLine();
        $this->info("✅ Proceso completado. {$contador} PINs configurados.");
        $this->newLine();
        
        $this->comment('📋 Instrucciones para los usuarios:');
        $this->comment('• Los usuarios pueden acceder con su PIN de 4 dígitos');
        $this->comment('• También pueden usar su email/username y contraseña');
        $this->comment('• El sistema está disponible en: /fichaje/login');
        $this->newLine();
        
        $this->info('🎯 ¡Sistema de fichaje listo para usar!');
    }
}
