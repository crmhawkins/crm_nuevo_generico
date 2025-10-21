<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Fichaje - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 20px 0;
            margin-bottom: 30px;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logo-section img {
            width: 50px;
            height: 50px;
            border-radius: 10px;
        }
        
        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
        }
        
        .user-section {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .logout-btn {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
        }
        
        .dashboard-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .fichaje-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
        }
        
        .status-indicator {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 10px;
        }
        
        .status-entrada { background: #51cf66; }
        .status-trabajando { background: #339af0; }
        .status-pausa { background: #ffd43b; }
        .status-salida { background: #ff6b6b; }
        
        .timer-display {
            font-size: 3rem;
            font-weight: bold;
            color: #333;
            margin: 20px 0;
            font-family: 'Courier New', monospace;
        }
        
        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .action-btn {
            padding: 20px;
            border: none;
            border-radius: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .btn-entrada {
            background: linear-gradient(135deg, #51cf66, #40c057);
        }
        
        .btn-salida {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
        }
        
        .btn-pausa {
            background: linear-gradient(135deg, #ffd43b, #fab005);
        }
        
        .btn-disabled {
            background: #6c757d;
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .btn-disabled:hover {
            transform: none;
            box-shadow: none;
        }
        
        .info-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 25px;
            margin-top: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            margin-bottom: 0;
        }
        
        .info-row:hover {
            background: rgba(0, 0, 0, 0.02);
            border-radius: 8px;
            padding-left: 10px;
            padding-right: 10px;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-row span:first-child {
            font-weight: 600;
            color: #495057;
            font-size: 0.95rem;
        }
        
        .info-row span:last-child {
            font-weight: 500;
            color: #212529;
            font-size: 0.95rem;
        }
        
        .info-section h5 {
            color: #495057;
            font-weight: 600;
            margin-bottom: 15px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        .historial-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 25px;
            margin-top: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .filtros-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 10px;
        }
        
        .filtro-group {
            display: flex;
            flex-direction: column;
        }
        
        .filtro-group label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }
        
        .filtro-group input, .filtro-group select {
            padding: 8px 12px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .filtro-group input:focus, .filtro-group select:focus {
            outline: none;
            border-color: #339af0;
            box-shadow: 0 0 0 3px rgba(51, 154, 240, 0.1);
        }
        
        .btn-filtrar {
            background: linear-gradient(135deg, #339af0 0%, #228be6 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            align-self: end;
        }
        
        .btn-filtrar:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(51, 154, 240, 0.3);
        }
        
        .jornadas-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .jornadas-table th {
            background: #495057;
            color: white;
            padding: 12px 8px;
            text-align: center;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .jornadas-table td {
            padding: 10px 8px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
            font-size: 0.85rem;
        }
        
        .jornadas-table tr:hover {
            background: rgba(0, 0, 0, 0.02);
        }
        
        .estado-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .estado-trabajando { background: #d1ecf1; color: #0c5460; }
        .estado-pausa { background: #fff3cd; color: #856404; }
        .estado-salida { background: #f8d7da; color: #721c24; }
        .estado-entrada { background: #d4edda; color: #155724; }
        
        .sin-datos {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-style: italic;
        }
        
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
        
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 20px;
            }
            
            .user-section {
                flex-direction: column;
                gap: 10px;
            }
            
            .action-buttons {
                grid-template-columns: 1fr;
            }
            
            .timer-display {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="logo-section">
                <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Logo">
                <span class="logo-text">Sistema de Fichaje</span>
            </div>
            <div class="user-section">
                <div class="user-info">
                    <div class="user-avatar">
                        {{ substr(Auth::user()->name, 0, 1) }}{{ substr(Auth::user()->surname, 0, 1) }}
                    </div>
                    <div>
                        <div>{{ Auth::user()->name }} {{ Auth::user()->surname }}</div>
                        <small class="text-muted">{{ Auth::user()->departamento->name ?? 'Sin departamento' }}</small>
                    </div>
                </div>
                <button class="logout-btn" onclick="logout()">
                    <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                </button>
            </div>
        </div>
    </header>

    <div class="dashboard-container">
        <div class="fichaje-card">
            <h2 class="mb-4">
                <span class="status-indicator status-{{ $fichajeHoy->estado }}"></span>
                Control de Jornada
            </h2>
            
            <div class="timer-display" id="timer">
                @if($fichajeHoy->hora_entrada && !$fichajeHoy->hora_salida)
                    @php
                        $horas = floor($tiempoTrabajado / 60);
                        $minutos = $tiempoTrabajado % 60;
                        $segundos = 0; // Para el timer en vivo
                    @endphp
                    {{ sprintf('%02d:%02d:%02d', $horas, $minutos, $segundos) }}
                @else
                    00:00:00
                @endif
            </div>
            
            <div class="mb-4">
                <h4 class="text-center text-muted">Tiempo Trabajado Hoy</h4>
            </div>
            
            <div class="info-section">
                <h5><i class="fas fa-info-circle me-2"></i>Información de Jornada</h5>
                
                <div class="info-row">
                    <span><i class="fas fa-calendar-alt me-2"></i>Fecha:</span>
                    <span>{{ $fichajeHoy->fecha->format('d/m/Y') }}</span>
                </div>
                
                <div class="info-row">
                    <span><i class="fas fa-circle me-2" style="color: {{ $fichajeHoy->estado === 'trabajando' ? '#339af0' : ($fichajeHoy->estado === 'pausa' ? '#ffd43b' : '#51cf66') }}"></i>Estado:</span>
                    <span id="estado-actual" style="color: {{ $fichajeHoy->estado === 'trabajando' ? '#339af0' : ($fichajeHoy->estado === 'pausa' ? '#ffd43b' : '#51cf66') }}; font-weight: 600;">{{ ucfirst($fichajeHoy->estado) }}</span>
                </div>
                
                @if($fichajeHoy->hora_entrada)
                <div class="info-row">
                    <span><i class="fas fa-sign-in-alt me-2"></i>Entrada:</span>
                    <span>{{ $fichajeHoy->hora_entrada->format('H:i:s') }}</span>
                </div>
                @endif
                
                @if($fichajeHoy->hora_salida)
                <div class="info-row">
                    <span><i class="fas fa-sign-out-alt me-2"></i>Salida:</span>
                    <span>{{ $fichajeHoy->hora_salida->format('H:i:s') }}</span>
                </div>
                @endif
                
                <div class="info-row">
                    <span><i class="fas fa-stopwatch me-2"></i>Tiempo Trabajado:</span>
                    <span style="color: #51cf66; font-weight: 600;">{{ sprintf('%02d:%02d', floor($tiempoTrabajado / 60), $tiempoTrabajado % 60) }}</span>
                </div>
                
                @if($fichajeHoy->hora_pausa_inicio)
                <div class="info-row">
                    <span><i class="fas fa-pause-circle me-2"></i>Pausas del día:</span>
                    <span>
                        @if($fichajeHoy->hora_pausa_inicio && $fichajeHoy->hora_pausa_fin)
                            {{ $fichajeHoy->hora_pausa_inicio->format('H:i') }} - {{ $fichajeHoy->hora_pausa_fin->format('H:i') }}
                        @elseif($fichajeHoy->hora_pausa_inicio && !$fichajeHoy->hora_pausa_fin)
                            <span style="color: #ffd43b; font-weight: 600;">{{ $fichajeHoy->hora_pausa_inicio->format('H:i') }} - En curso</span>
                        @endif
                    </span>
                </div>
                @endif
                
                @if($fichajeHoy->tiempo_pausa > 0)
                <div class="info-row">
                    <span>Tiempo Total en Pausa:</span>
                    <span>{{ sprintf('%02d:%02d', floor($fichajeHoy->tiempo_pausa / 60), $fichajeHoy->tiempo_pausa % 60) }}</span>
                </div>
                @endif
            </div>
            
            <div class="action-buttons">
                <button class="action-btn btn-entrada {{ $fichajeHoy->hora_entrada ? 'btn-disabled' : '' }}" 
                        onclick="ficharEntrada()" 
                        {{ $fichajeHoy->hora_entrada ? 'disabled' : '' }}>
                    <i class="fas fa-sign-in-alt"></i>
                    Entrada
                </button>
                
                <button class="action-btn btn-pausa {{ !$fichajeHoy->hora_entrada || $fichajeHoy->hora_salida ? 'btn-disabled' : '' }}" 
                        onclick="ficharPausa()" 
                        {{ !$fichajeHoy->hora_entrada || $fichajeHoy->hora_salida ? 'disabled' : '' }}>
                    <i class="fas fa-pause"></i>
                    <span id="pausa-texto">
                        {{ $fichajeHoy->estado === 'pausa' ? 'Finalizar Pausa' : 'Iniciar Pausa' }}
                    </span>
                </button>
                
                <button class="action-btn btn-salida {{ !$fichajeHoy->hora_entrada || $fichajeHoy->hora_salida ? 'btn-disabled' : '' }}" 
                        onclick="ficharSalida()" 
                        {{ !$fichajeHoy->hora_entrada || $fichajeHoy->hora_salida ? 'disabled' : '' }}>
                    <i class="fas fa-sign-out-alt"></i>
                    Salida
                </button>
            </div>
        </div>
        
        <!-- Sección de Historial de Jornadas -->
        <div class="historial-section">
            <h5><i class="fas fa-history me-2"></i>Historial de Jornadas</h5>
            
            <!-- Filtros -->
            <div class="filtros-container">
                <div class="filtro-group">
                    <label for="fecha_desde">Desde:</label>
                    <input type="date" id="fecha_desde" name="fecha_desde">
                </div>
                
                <div class="filtro-group">
                    <label for="fecha_hasta">Hasta:</label>
                    <input type="date" id="fecha_hasta" name="fecha_hasta">
                </div>
                
                <div class="filtro-group">
                    <label for="mes">Mes:</label>
                    <select id="mes" name="mes">
                        <option value="">Todos los meses</option>
                        <option value="1">Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                    </select>
                </div>
                
                <div class="filtro-group">
                    <label for="año">Año:</label>
                    <select id="año" name="año">
                        <option value="">Todos los años</option>
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                    </select>
                </div>
                
                <button class="btn-filtrar" onclick="filtrarJornadas()">
                    <i class="fas fa-search me-2"></i>Filtrar
                </button>
            </div>
            
            <!-- Tabla de Jornadas -->
            <div id="tabla-jornadas">
                <table class="jornadas-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Tiempo Trabajado</th>
                            <th>Tiempo Pausa</th>
                            <th>Pausas</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody id="jornadas-tbody">
                        @forelse($jornadas as $jornada)
                        <tr>
                            <td>{{ $jornada->fecha->format('d/m/Y') }}</td>
                            <td>{{ $jornada->hora_entrada ? $jornada->hora_entrada->format('H:i:s') : '-' }}</td>
                            <td>{{ $jornada->hora_salida ? $jornada->hora_salida->format('H:i:s') : '-' }}</td>
                            <td>{{ sprintf('%02d:%02d', floor($jornada->tiempo_trabajado / 60), $jornada->tiempo_trabajado % 60) }}</td>
                            <td>{{ sprintf('%02d:%02d', floor($jornada->tiempo_pausa / 60), $jornada->tiempo_pausa % 60) }}</td>
                            <td>
                                @if($jornada->hora_pausa_inicio)
                                    @if($jornada->hora_pausa_fin)
                                        {{ $jornada->hora_pausa_inicio->format('H:i') }} - {{ $jornada->hora_pausa_fin->format('H:i') }}
                                    @else
                                        <span style="color: #ffd43b;">{{ $jornada->hora_pausa_inicio->format('H:i') }} - En curso</span>
                                    @endif
                                @else
                                    Sin pausas
                                @endif
                            </td>
                            <td>
                                <span class="estado-badge estado-{{ $jornada->estado }}">
                                    {{ ucfirst($jornada->estado) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="sin-datos">
                                <i class="fas fa-info-circle me-2"></i>No hay jornadas registradas
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let timerInterval;
        let startTime = null;
        
        // Inicializar timer si hay entrada y no está en pausa
        @if($fichajeHoy->hora_entrada && !$fichajeHoy->hora_salida && $fichajeHoy->estado !== 'pausa')
            startTime = new Date('{{ $fichajeHoy->fecha->format('Y-m-d') }} {{ $fichajeHoy->hora_entrada->format('H:i:s') }}');
            // Mostrar tiempo trabajado actual desde el servidor
            document.getElementById('timer').textContent = '{{ sprintf("%02d:%02d:%02d", floor($tiempoTrabajado / 60), $tiempoTrabajado % 60, 0) }}';
            updateTimer();
            timerInterval = setInterval(updateTimer, 1000);
        @elseif($fichajeHoy->hora_entrada && !$fichajeHoy->hora_salida && $fichajeHoy->estado === 'pausa')
            // Si está en pausa, mostrar tiempo trabajado sin actualizar
            document.getElementById('timer').textContent = '{{ sprintf("%02d:%02d:%02d", floor($tiempoTrabajado / 60), $tiempoTrabajado % 60, 0) }}';
        @endif
        
        function updateTimer() {
            if (startTime) {
                const now = new Date();
                const diff = now - startTime;
                
                // Calcular tiempo total en minutos
                const totalMinutes = Math.floor(diff / (1000 * 60));
                
                // Tiempo de pausa acumulado desde el backend
                let tiempoPausaTotal = {{ $fichajeHoy->tiempo_pausa ?? 0 }};
                
                // Si está en pausa, sumar tiempo de pausa actual
                @if($fichajeHoy->estado === 'pausa' && $fichajeHoy->hora_pausa_inicio)
                    const inicioPausa = new Date('{{ $fichajeHoy->fecha->format('Y-m-d') }} {{ $fichajeHoy->hora_pausa_inicio->format('H:i:s') }}');
                    const tiempoPausaActual = Math.floor((now - inicioPausa) / (1000 * 60));
                    tiempoPausaTotal += tiempoPausaActual;
                @endif
                
                // Tiempo trabajado = tiempo total - tiempo de pausa
                const tiempoTrabajado = Math.max(0, totalMinutes - tiempoPausaTotal);
                
                const hours = Math.floor(tiempoTrabajado / 60);
                const minutes = tiempoTrabajado % 60;
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                
                document.getElementById('timer').textContent = 
                    String(hours).padStart(2, '0') + ':' + 
                    String(minutes).padStart(2, '0') + ':' + 
                    String(seconds).padStart(2, '0');
            }
        }
        
        function ficharEntrada() {
            if (document.querySelector('.btn-entrada').classList.contains('btn-disabled')) return;
            
            fetch('{{ route("fichaje.entrada") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Entrada fichada!',
                        text: data.message,
                        timer: 2000
                    });
                    // Iniciar el timer inmediatamente
                    startTime = new Date();
                    updateTimer();
                    timerInterval = setInterval(updateTimer, 1000);
                    location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            });
        }
        
        function ficharSalida() {
            if (document.querySelector('.btn-salida').classList.contains('btn-disabled')) return;
            
            fetch('{{ route("fichaje.salida") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Salida fichada!',
                        text: data.message,
                        timer: 2000
                    });
                    location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            });
        }
        
        function ficharPausa() {
            if (document.querySelector('.btn-pausa').classList.contains('btn-disabled')) return;
            
            // Parar el timer inmediatamente
            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }
            
            fetch('{{ route("fichaje.pausa") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Pausa actualizada!',
                        text: data.message,
                        timer: 2000
                    });
                    location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            });
        }
        
        function logout() {
            Swal.fire({
                title: '¿Cerrar sesión?',
                text: '¿Estás seguro de que quieres cerrar sesión?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ff6b6b',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, cerrar sesión',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route("fichaje.logout") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(() => {
                        window.location.href = '{{ route("fichaje.login") }}';
                    });
                }
            });
        }
        
        function filtrarJornadas() {
            const fechaDesde = document.getElementById('fecha_desde').value;
            const fechaHasta = document.getElementById('fecha_hasta').value;
            const mes = document.getElementById('mes').value;
            const año = document.getElementById('año').value;
            
            const formData = new FormData();
            if (fechaDesde) formData.append('fecha_desde', fechaDesde);
            if (fechaHasta) formData.append('fecha_hasta', fechaHasta);
            if (mes) formData.append('mes', mes);
            if (año) formData.append('año', año);
            
            fetch('{{ route("fichaje.filtrar-jornadas") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    actualizarTablaJornadas(data.jornadas);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al filtrar jornadas'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión'
                });
            });
        }
        
        function actualizarTablaJornadas(jornadas) {
            const tbody = document.getElementById('jornadas-tbody');
            
            if (jornadas.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="sin-datos">
                            <i class="fas fa-info-circle me-2"></i>No se encontraron jornadas con los filtros aplicados
                        </td>
                    </tr>
                `;
                return;
            }
            
            tbody.innerHTML = jornadas.map(jornada => `
                <tr>
                    <td>${jornada.fecha}</td>
                    <td>${jornada.hora_entrada}</td>
                    <td>${jornada.hora_salida}</td>
                    <td>${jornada.tiempo_trabajado}</td>
                    <td>${jornada.tiempo_pausa}</td>
                    <td>${jornada.pausas}</td>
                    <td>
                        <span class="estado-badge estado-${jornada.estado.toLowerCase()}">
                            ${jornada.estado}
                        </span>
                    </td>
                </tr>
            `).join('');
        }
    </script>
</body>
</html>
