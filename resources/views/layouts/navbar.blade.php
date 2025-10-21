<!-- Navbar Responsive -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom">
    <div class="container-fluid">
        <!-- Logo/Brand -->
        <a class="navbar-brand d-flex align-items-center" href="/dashboard">
            <img src="{{asset('assets/images/logo/logo.png')}}" alt="Logo" height="28" class="me-2" style="max-width: 200px;">
            <span class="fw-bold text-primary fs-6">CRM</span>
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Main Navigation -->
            <ul class="navbar-nav me-auto">
                @php
                    $jornadasActive = request()->routeIs('horas.*');
                    $departamentoActive = request()->routeIs('departamento.*');
                    $cargoActive = request()->routeIs('cargo.*');
                    $personalActive = request()->routeIs('users.*');
                    $cofiguracionActive = request()->routeIs('configuracion.*');
                    $admin = (Auth::user()->access_level_id == 1);
                    $gerente = (Auth::user()->access_level_id == 2);
                    $contable = (Auth::user()->access_level_id == 3);
                @endphp

                <!-- Jornadas Dropdown -->
                @if ($admin || $gerente || $contable)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center {{ $jornadasActive ? 'active' : '' }}" 
                       href="#" id="jornadasDropdown" role="button" aria-expanded="false">
                        <i class="fa-solid fa-clock me-2 text-primary"></i>
                        <span>Jornadas</span>
                    </a>
                    <ul class="dropdown-menu shadow-sm border-0" aria-labelledby="jornadasDropdown">
                        <li>
                            <a class="dropdown-item d-flex align-items-center {{ request()->routeIs('horas.index') ? 'active' : '' }}" 
                               href="{{route('horas.index')}}">
                                <i class="fa-solid fa-list me-2 text-muted"></i>
                                Ver Jornadas
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center {{ request()->routeIs('horas.listado') ? 'active' : '' }}" 
                               href="{{route('horas.listado')}}">
                                <i class="fa-solid fa-list me-2 text-muted"></i>
                                Listado de jornadas
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center {{ request()->routeIs('horas.create') ? 'active' : '' }}" 
                               href="{{route('horas.create')}}">
                                <i class="fa-solid fa-plus me-2 text-muted"></i>
                                Añadir jornada
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if ($admin || $gerente)
                <!-- Departamentos Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center {{ $departamentoActive ? 'active' : '' }}" 
                       href="#" id="departamentosDropdown" role="button" aria-expanded="false">
                        <i class="fa-solid fa-building me-2 text-success"></i>
                        <span>Departamentos</span>
                    </a>
                    <ul class="dropdown-menu shadow-sm border-0" aria-labelledby="departamentosDropdown">
                        <li>
                            <a class="dropdown-item d-flex align-items-center {{ request()->routeIs('departamento.index') ? 'active' : '' }}" 
                               href="{{route('departamento.index')}}">
                                <i class="fa-solid fa-list me-2 text-muted"></i>
                                Ver todos
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center {{ request()->routeIs('departamento.create') ? 'active' : '' }}" 
                               href="{{route('departamento.create')}}">
                                <i class="fa-solid fa-plus me-2 text-muted"></i>
                                Crear departamento
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Cargos Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center {{ $cargoActive ? 'active' : '' }}" 
                       href="#" id="cargosDropdown" role="button" aria-expanded="false">
                        <i class="fa-solid fa-user-tie me-2 text-warning"></i>
                        <span>Cargos</span>
                    </a>
                    <ul class="dropdown-menu shadow-sm border-0" aria-labelledby="cargosDropdown">
                        <li>
                            <a class="dropdown-item d-flex align-items-center {{ request()->routeIs('cargo.index') ? 'active' : '' }}" 
                               href="{{route('cargo.index')}}">
                                <i class="fa-solid fa-list me-2 text-muted"></i>
                                Ver todos
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center {{ request()->routeIs('cargo.create') ? 'active' : '' }}" 
                               href="{{route('cargo.create')}}">
                                <i class="fa-solid fa-plus me-2 text-muted"></i>
                                Crear cargo
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Personal Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center {{ $personalActive ? 'active' : '' }}" 
                       href="#" id="personalDropdown" role="button" aria-expanded="false">
                        <i class="fa-solid fa-users me-2 text-info"></i>
                        <span>Personal</span>
                    </a>
                    <ul class="dropdown-menu shadow-sm border-0" aria-labelledby="personalDropdown">
                        <li>
                            <a class="dropdown-item d-flex align-items-center {{ request()->routeIs('users.index') ? 'active' : '' }}" 
                               href="{{route('users.index')}}">
                                <i class="fa-solid fa-list me-2 text-muted"></i>
                                Ver todos
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center {{ request()->routeIs('users.create') ? 'active' : '' }}" 
                               href="{{route('users.create')}}">
                                <i class="fa-solid fa-plus me-2 text-muted"></i>
                                Crear usuario
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Configuración Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center {{ $cofiguracionActive ? 'active' : '' }}" 
                       href="#" id="configuracionDropdown" role="button" aria-expanded="false">
                        <i class="fa-solid fa-gear me-2 text-secondary"></i>
                        <span>Configuración</span>
                    </a>
                    <ul class="dropdown-menu shadow-sm border-0" aria-labelledby="configuracionDropdown">
                        <li>
                            <a class="dropdown-item d-flex align-items-center {{ request()->routeIs('configuracion.index') ? 'active' : '' }}" 
                               href="{{route('configuracion.index')}}">
                                <i class="fa-solid fa-building me-2 text-muted"></i>
                                Configuración Empresa
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
            </ul>

            <!-- Right Side - User Info & Notifications -->
            <ul class="navbar-nav">
                <!-- Notifications -->
                <li class="nav-item dropdown me-3">
                    <a class="nav-link position-relative" href="#" id="notificationsDropdown" role="button" 
                       aria-expanded="false">
                        <i class="fa-solid fa-bell fs-5 text-muted"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            0
                            <span class="visually-hidden">notificaciones</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="notificationsDropdown">
                        <li><h6 class="dropdown-header">Notificaciones</h6></li>
                        <li><a class="dropdown-item" href="#">No hay notificaciones</a></li>
                    </ul>
                </li>

                <!-- User Profile -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" 
                       role="button" aria-expanded="false">
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" 
                             style="width: 32px; height: 32px;">
                            <i class="fa-solid fa-user text-white"></i>
                        </div>
                        <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'Usuario' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="userDropdown">
                        <li><h6 class="dropdown-header">{{ Auth::user()->name ?? 'Usuario' }}</h6></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <i class="fa-solid fa-user me-2 text-muted"></i>
                                Perfil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <i class="fa-solid fa-gear me-2 text-muted"></i>
                                Configuración
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center text-danger" 
                               href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa-solid fa-sign-out-alt me-2"></i>
                                Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Logout Form (Hidden) -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>

<!-- Script específico para dropdowns del navbar -->
<script>
// Función para cerrar todos los dropdowns
function closeAllDropdowns() {
    const openDropdowns = document.querySelectorAll('.dropdown-menu.show');
    openDropdowns.forEach(function(dropdown) {
        dropdown.classList.remove('show');
    });
}

// Función para manejar clicks en dropdowns
function handleDropdownClick(e) {
    console.log('🎯 ¡CLICK DETECTADO!', this.textContent.trim());
    e.preventDefault();
    e.stopPropagation();
    
    // Cerrar otros dropdowns primero
    closeAllDropdowns();
    
    // Toggle del dropdown actual
    const dropdownMenu = this.nextElementSibling;
    if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
        dropdownMenu.classList.add('show');
        console.log('✅ Dropdown abierto:', this.textContent.trim());
    } else {
        console.log('❌ ERROR: No se encontró el dropdown menu');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Inicializando dropdowns del navbar...');
    
    // Esperar un poco para asegurar que el DOM esté completamente cargado
    setTimeout(function() {
        // Inicializar dropdowns específicamente
        const dropdowns = document.querySelectorAll('.dropdown-toggle');
        console.log('📋 Encontrados', dropdowns.length, 'dropdowns');
        
        dropdowns.forEach(function(dropdown, index) {
            console.log('⚙️ Configurando dropdown', index + 1, ':', dropdown.textContent.trim());
            
            // Remover cualquier event listener existente
            dropdown.onclick = null;
            
            // Agregar el event listener
            dropdown.addEventListener('click', handleDropdownClick);
        });
        
        // Cerrar dropdowns al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown')) {
                closeAllDropdowns();
            }
        });
        
        console.log('✅ Dropdowns del navbar inicializados correctamente');
        
        // Prueba manual después de 3 segundos
        setTimeout(function() {
            console.log('🧪 === PRUEBA MANUAL DE DROPDOWN ===');
            const testDropdown = document.querySelector('#jornadasDropdown');
            if (testDropdown) {
                console.log('🔍 Probando dropdown de Jornadas...');
                const testMenu = testDropdown.nextElementSibling;
                if (testMenu) {
                    testMenu.classList.add('show');
                    console.log('✅ Dropdown de prueba abierto manualmente');
                    setTimeout(function() {
                        testMenu.classList.remove('show');
                        console.log('❌ Dropdown de prueba cerrado');
                    }, 2000);
                }
            }
        }, 3000);
        
    }, 500);
});
</script>

<!-- Custom CSS for Navbar -->
<style>
.navbar {
    padding: 0.5rem 1rem;
    transition: all 0.3s ease;
    min-height: 60px;
}

.navbar-brand {
    font-size: 1.1rem;
    font-weight: 600;
    color: #6366f1 !important;
    text-decoration: none;
}

.navbar-brand:hover {
    color: #4f46e5 !important;
}

        .navbar-brand img {
            max-width: 200px !important;
            height: auto !important;
            max-height: 40px !important;
        }
        
        /* Ocultar cualquier texto "LOGO" gigante */
        .navbar-brand::before,
        .navbar-brand::after {
            display: none !important;
        }
        
        /* Asegurar que solo se muestre la imagen */
        .navbar-brand {
            font-size: 0 !important;
            line-height: 0 !important;
        }
        
        .navbar-brand img {
            display: block !important;
        }

.nav-link {
    font-weight: 500;
    color: #374151 !important;
    padding: 0.75rem 1rem !important;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    position: relative;
}

.nav-link:hover {
    color: #1f2937 !important;
    background-color: #f3f4f6;
    transform: translateY(-1px);
}

.nav-link.active {
    color: #6366f1 !important;
    background-color: #eef2ff;
    font-weight: 600;
}

.dropdown-menu {
    border: none;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    border-radius: 0.75rem;
    padding: 0.5rem 0;
    margin-top: 0.5rem;
    display: none !important;
    position: absolute !important;
    z-index: 1000 !important;
    background-color: white !important;
    min-width: 200px !important; /* Ancho mínimo fijo */
    width: auto !important; /* Ancho automático basado en contenido */
    left: 0 !important; /* Alineado a la izquierda */
    white-space: nowrap !important; /* Evitar que el texto se corte */
}

.dropdown-menu.show {
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
}

/* Asegurar que los dropdowns estén posicionados correctamente */
.nav-item.dropdown {
    position: relative !important;
}

.dropdown-menu {
    top: 100% !important;
    left: 0 !important;
    right: 0 !important;
    transform: none !important;
    margin-top: 0.25rem !important; /* Espaciado más pequeño */
}

/* Para dropdowns del lado derecho */
.dropdown-menu-end {
    left: auto !important;
    right: 0 !important;
}

/* Mejorar la apariencia de los items del dropdown */
.dropdown-item {
    padding: 0.75rem 1.5rem !important; /* Más padding horizontal */
    font-size: 0.9rem !important;
    transition: all 0.2s ease !important;
    white-space: nowrap !important; /* Evitar que el texto se corte */
    display: block !important; /* Asegurar que sea un bloque */
    width: 100% !important; /* Ocupar todo el ancho disponible */
    min-width: max-content !important; /* Ancho mínimo basado en contenido */
}

.dropdown-item:hover {
    background-color: #f8fafc !important;
    color: #6366f1 !important;
}

.dropdown-item.active {
    background-color: #eef2ff !important;
    color: #6366f1 !important;
    font-weight: 600 !important;
}

/* Los estilos de dropdown-item ya están definidos arriba con !important */

.dropdown-header {
    font-weight: 600;
    color: #6b7280;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0.5rem 1.5rem 0.25rem;
}

.navbar-toggler {
    border: none;
    padding: 0.25rem 0.5rem;
}

.navbar-toggler:focus {
    box-shadow: none;
}

/* Responsive adjustments */
@media (max-width: 991.98px) {
    .navbar-collapse {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e5e7eb;
    }
    
    .nav-link {
        padding: 0.5rem 0 !important;
        margin: 0.25rem 0;
    }
    
    .dropdown-menu {
        position: static !important;
        transform: none !important;
        box-shadow: none;
        border: 1px solid #e5e7eb;
        margin: 0.5rem 0;
    }
}

/* Animation for mobile menu */
.navbar-collapse.collapsing {
    transition: height 0.35s ease;
}

/* Icon colors */
.fa-clock { color: #6366f1 !important; }
.fa-building { color: #10b981 !important; }
.fa-user-tie { color: #f59e0b !important; }
.fa-users { color: #06b6d4 !important; }
.fa-gear { color: #6b7280 !important; }

/* Badge animation */
.badge {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

/* Hover effects for brand */
.navbar-brand:hover img {
    transform: scale(1.05);
    transition: transform 0.3s ease;
}
</style>
