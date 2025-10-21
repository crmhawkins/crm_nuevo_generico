<?php

namespace App\Http\Controllers;

use App\Models\Users\User;
use App\Models\Fichaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class FichajeController extends Controller
{
    public function showLogin()
    {
        // Si ya está autenticado, redirigir al dashboard
        if (Auth::check()) {
            return redirect()->route('fichaje.dashboard');
        }
        
        return view('fichaje.login_simple');
    }

    public function login(Request $request)
    {
        \Log::info('Login attempt - Datos recibidos:', $request->all());
        
        $validator = Validator::make($request->all(), [
            'metodo' => 'required|in:pin,password'
        ]);
        
        // Validar campos según el método
        if ($request->metodo === 'pin') {
            $validator->addRules(['pin_code' => 'required|string|size:4|regex:/^[0-9]{4}$/']);
        } else {
            $validator->addRules([
                'identificador' => 'required|string',
                'password' => 'required|string'
            ]);
        }

        if ($validator->fails()) {
            \Log::info('Validación falló:', $validator->errors()->toArray());
            return back()->withErrors($validator)->withInput();
        }

        // Determinar el identificador según el método
        $identificador = $request->metodo === 'pin' ? $request->pin_code : $request->identificador;
        $metodo = $request->metodo;
        \Log::info('Identificador: ' . $identificador . ', Método: ' . $metodo);
        $user = null;

        if ($metodo === 'pin') {
            // Validar que el PIN tenga 4 dígitos
            if (!preg_match('/^\d{4}$/', $identificador)) {
                \Log::info('PIN inválido: ' . $identificador . ' (longitud: ' . strlen($identificador) . ')');
                return back()->withErrors(['login' => 'El PIN debe tener exactamente 4 dígitos'])->withInput();
            }
            
            \Log::info('Buscando usuario con PIN: ' . $identificador);
            $user = User::where('pin', $identificador)
                      ->where('pin_activo', true)
                      ->where('inactive', 0)
                      ->first();
            
            \Log::info('Usuario encontrado: ' . ($user ? 'Sí' : 'No'));
        } else {
            // Login con email/username y contraseña
            $user = User::where('email', $identificador)
                      ->orWhere('name', $identificador)
                      ->where('inactive', 0)
                      ->first();
            
            if ($user && !Hash::check($request->password, $user->password)) {
                $user = null;
            }
        }

        if ($user) {
            Auth::login($user);
            $user->update(['ultimo_acceso' => now()]);
            
            return redirect()->route('fichaje.dashboard')->with('success', 'Bienvenido, ' . $user->name);
        }

        return back()->withErrors(['login' => 'Credenciales incorrectas o usuario inactivo'])->withInput();
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('fichaje.login')->with('success', 'Sesión cerrada correctamente');
    }

    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('fichaje.login');
        }

        // Verificar timeout de sesión (30 minutos)
        $user = Auth::user();
        if ($user->ultimo_acceso && $user->ultimo_acceso->diffInMinutes(now()) > 30) {
            Auth::logout();
            return redirect()->route('fichaje.login')->with('error', 'Sesión expirada por inactividad');
        }

        $hoy = Carbon::today();
        
        // Obtener fichaje del día actual
        $fichajeHoy = Fichaje::where('user_id', $user->id)
                           ->where('fecha', $hoy)
                           ->first();
        
        // Si no existe fichaje para hoy, crear uno
        if (!$fichajeHoy) {
            $fichajeHoy = Fichaje::create([
                'user_id' => $user->id,
                'fecha' => $hoy,
                'estado' => 'entrada'
            ]);
        }
        
            // Calcular tiempo trabajado descontando pausas
            $tiempoTrabajado = 0;
            $tiempoPausaTotal = $fichajeHoy->tiempo_pausa ?? 0;
            
            if ($fichajeHoy->hora_entrada) {
                $inicio = Carbon::parse($fichajeHoy->fecha->format('Y-m-d') . ' ' . $fichajeHoy->hora_entrada->format('H:i:s'));
                $ahora = Carbon::now();
                
                // Calcular tiempo total desde entrada
                $tiempoTotal = $inicio->diffInMinutes($ahora);
                
                // Si hay pausa en curso, sumar tiempo de pausa actual
                if ($fichajeHoy->estado === 'pausa' && $fichajeHoy->hora_pausa_inicio) {
                    $inicioPausa = Carbon::parse($fichajeHoy->fecha->format('Y-m-d') . ' ' . $fichajeHoy->hora_pausa_inicio->format('H:i:s'));
                    $tiempoPausaActual = round($inicioPausa->diffInSeconds($ahora) / 60, 2);
                    $tiempoPausaTotal = ($fichajeHoy->tiempo_pausa ?? 0) + $tiempoPausaActual;
                }
                
                // Tiempo trabajado = tiempo total - tiempo de pausa
                $tiempoTrabajado = max(0, $tiempoTotal - $tiempoPausaTotal);
            }
        
        // Obtener historial de jornadas del usuario
        $jornadas = Fichaje::where('user_id', $user->id)
                          ->orderBy('fecha', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->limit(20)
                          ->get();
        
        return view('dashboards.dashboard_fichaje_funcional', compact('fichajeHoy', 'tiempoTrabajado', 'jornadas'));
    }

    public function cambiarMetodoLogin(Request $request)
    {
        $user = Auth::user();
        $metodo = $request->metodo;
        
        if ($metodo === 'pin') {
            $user->update(['metodo_login' => 'pin']);
        } else {
            $user->update(['metodo_login' => 'password']);
        }
        
        return response()->json(['success' => true, 'message' => 'Método de login actualizado']);
    }
    
    public function ficharEntrada(Request $request)
    {
        $user = Auth::user();
        $hoy = Carbon::today();
        
        $fichaje = Fichaje::where('user_id', $user->id)
                         ->where('fecha', $hoy)
                         ->first();
        
        if (!$fichaje) {
            $fichaje = Fichaje::create([
                'user_id' => $user->id,
                'fecha' => $hoy,
                'estado' => 'entrada'
            ]);
        }
        
        if ($fichaje->hora_entrada) {
            return response()->json(['success' => false, 'message' => 'Ya has fichado la entrada hoy']);
        }
        
        $fichaje->update([
            'hora_entrada' => Carbon::now()->format('H:i:s'),
            'estado' => 'trabajando'
        ]);
        
        return response()->json(['success' => true, 'message' => 'Entrada fichada correctamente']);
    }
    
    public function ficharSalida(Request $request)
    {
        $user = Auth::user();
        $hoy = Carbon::today();
        
        $fichaje = Fichaje::where('user_id', $user->id)
                         ->where('fecha', $hoy)
                         ->first();
        
        if (!$fichaje || !$fichaje->hora_entrada) {
            return response()->json(['success' => false, 'message' => 'No has fichado la entrada']);
        }
        
        if ($fichaje->hora_salida) {
            return response()->json(['success' => false, 'message' => 'Ya has fichado la salida']);
        }
        
        $fichaje->update([
            'hora_salida' => Carbon::now()->format('H:i:s'),
            'estado' => 'salida'
        ]);
        
        return response()->json(['success' => true, 'message' => 'Salida fichada correctamente']);
    }
    
    public function ficharPausa(Request $request)
    {
        $user = Auth::user();
        $hoy = Carbon::today();
        
        $fichaje = Fichaje::where('user_id', $user->id)
                         ->where('fecha', $hoy)
                         ->first();
        
        if (!$fichaje || !$fichaje->hora_entrada) {
            return response()->json(['success' => false, 'message' => 'No has fichado la entrada']);
        }
        
        if ($fichaje->hora_salida) {
            return response()->json(['success' => false, 'message' => 'Ya has fichado la salida']);
        }
        
        if ($fichaje->estado === 'pausa') {
            // Finalizar pausa
            $horaPausaFin = Carbon::now();
            $horaPausaInicio = Carbon::parse($fichaje->fecha->format('Y-m-d') . ' ' . $fichaje->hora_pausa_inicio->format('H:i:s'));
            
            // Calcular duración de la pausa
            $duracionPausa = $horaPausaInicio->diffInSeconds($horaPausaFin);
            $tiempoPausaActual = round($duracionPausa / 60, 2);
            
            // Sumar a tiempo de pausa acumulado
            $tiempoPausaTotal = ($fichaje->tiempo_pausa ?? 0) + $tiempoPausaActual;
            
            $fichaje->update([
                'hora_pausa_fin' => $horaPausaFin->format('H:i:s'),
                'estado' => 'trabajando',
                'tiempo_pausa' => $tiempoPausaTotal
            ]);
            
            return response()->json(['success' => true, 'message' => 'Pausa finalizada']);
        } else {
            // Iniciar pausa
            $fichaje->update([
                'hora_pausa_inicio' => Carbon::now()->format('H:i:s'),
                'estado' => 'pausa'
            ]);
            
            return response()->json(['success' => true, 'message' => 'Pausa iniciada']);
        }
    }
    
    public function filtrarJornadas(Request $request)
    {
        $user = Auth::user();
        $query = Fichaje::where('user_id', $user->id);
        
        // Filtro por fecha
        if ($request->fecha) {
            $query->where('fecha', $request->fecha);
        }
        
        // Filtro por mes
        if ($request->mes) {
            $query->whereMonth('fecha', $request->mes);
        }
        
        // Filtro por año
        if ($request->año) {
            $query->whereYear('fecha', $request->año);
        }
        
        // Filtro por rango de fechas
        if ($request->fecha_desde && $request->fecha_hasta) {
            $query->whereBetween('fecha', [$request->fecha_desde, $request->fecha_hasta]);
        }
        
        $jornadas = $query->orderBy('fecha', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->limit(50)
                         ->get();
        
        return response()->json([
            'success' => true,
            'jornadas' => $jornadas->map(function($jornada) {
                return [
                    'id' => $jornada->id,
                    'fecha' => $jornada->fecha->format('d/m/Y'),
                    'hora_entrada' => $jornada->hora_entrada ? $jornada->hora_entrada->format('H:i:s') : '-',
                    'hora_salida' => $jornada->hora_salida ? $jornada->hora_salida->format('H:i:s') : '-',
                    'tiempo_trabajado' => sprintf('%02d:%02d', floor($jornada->tiempo_trabajado / 60), $jornada->tiempo_trabajado % 60),
                    'tiempo_pausa' => sprintf('%02d:%02d', floor($jornada->tiempo_pausa / 60), $jornada->tiempo_pausa % 60),
                    'estado' => ucfirst($jornada->estado),
                    'pausas' => $jornada->hora_pausa_inicio ? 
                        ($jornada->hora_pausa_fin ? 
                            $jornada->hora_pausa_inicio->format('H:i') . ' - ' . $jornada->hora_pausa_fin->format('H:i') : 
                            $jornada->hora_pausa_inicio->format('H:i') . ' - En curso') : 
                        'Sin pausas'
                ];
            })
        ]);
    }
}

