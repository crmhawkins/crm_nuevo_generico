<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FichajeAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('fichaje.login')->with('error', 'Debes iniciar sesión para acceder al sistema de fichaje');
        }

        $user = Auth::user();

        // Verificar si el usuario está activo
        if ($user->inactive) {
            Auth::logout();
            return redirect()->route('fichaje.login')->with('error', 'Tu cuenta está inactiva. Contacta con el administrador');
        }

        // Verificar timeout de sesión (30 minutos)
        if ($user->ultimo_acceso && $user->ultimo_acceso->diffInMinutes(now()) > 30) {
            Auth::logout();
            return redirect()->route('fichaje.login')->with('error', 'Sesión expirada por inactividad. Vuelve a iniciar sesión');
        }

        // Actualizar último acceso
        $user->update(['ultimo_acceso' => now()]);

        return $next($request);
    }
}
