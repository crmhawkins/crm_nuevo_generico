<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('titulo') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts y estilos -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/iconly/bold.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <!-- CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
        {{-- @if ($isDarkMode)
            @vite(['resources/sass/dark-mode.scss'])
        @else
            @vite(['resources/sass/light-mode.scss'])
        @endif --}}
        {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/choices.js/1.1.6/styles/css/base.css" integrity="sha512-Am/2bbv2qivU11QVLRLepSHQjZ98V1DWpWlwNyFF5XRYNlHLrKUusGYrVocdIko13v4Nl6AbvfJb87wQJyxyQA==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/choices.js/1.1.6/choices.min.js" integrity="sha512-7PQ3MLNFhvDn/IQy12+1+jKcc1A/Yx4KuL62Bn6+ztkiitRVW1T/7ikAh675pOs3I+8hyXuRknDpTteeptw4Bw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        @yield('css')
        <link rel="stylesheet" href="{{ asset('build/assets/app-d2e38ed8.css') }}" crossorigin="anonymous" referrerpolicy="no-referrer">
        <script src="{{ asset('build/assets/app-bf7e6802.js') }}"></script>
        @laravelViewsStyles
        {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
        
        <!-- Custom Styles for Navbar Layout -->
        <style>
            /* Reset any sidebar margins */
            #main {
                margin-left: 0 !important;
                margin-top: 0 !important;
                width: 100% !important;
            }
            
            .main-content {
                margin-top: 0;
                min-height: calc(100vh - 60px);
                background-color: #f8fafc;
                margin-left: 0 !important;
                padding-left: 0 !important;
            }
            
            body {
                background-color: #f8fafc;
                margin: 0;
                padding: 0;
            }
            
            /* Ensure full width */
            .container-fluid {
                padding-left: 15px !important;
                padding-right: 15px !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
            
            /* Smooth transitions */
            * {
                transition: all 0.3s ease;
            }
            
            /* Card styling improvements */
            .card {
                border: none;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
                border-radius: 0.75rem;
            }
            
            .card:hover {
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                transform: translateY(-2px);
            }
            
            /* Override any existing sidebar styles */
            #sidebar {
                display: none !important;
            }
            
            /* Hide any LOGO text that might be appearing */
            .logo-text-large,
            .header-logo,
            .main-logo {
                display: none !important;
            }
            
            /* Force full width layout */
            .contenedor {
                margin-left: 0 !important;
                padding-left: 15px !important;
                padding-right: 15px !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            
            /* Override any potential sidebar margins */
            body, html {
                margin: 0 !important;
                padding: 0 !important;
            }
            
            #app {
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
            }
        </style>
</head>
<body class="" style="overflow-x: hidden">
    <div id="app">
        <div id="loadingOverlay" style="display: block; position: fixed; width: 100%; height: 100%; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(255,255,255,0.5); z-index: 50000; cursor: pointer;">
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                <div class="spinner-border text-black" role="status">
                    <span class="sr-only">Cargando...</span>
                </div>
            </div>
        </div>
        <div class="css-96uzu9"></div>

        <!-- Navbar -->
        @include('layouts.navbar')

        <!-- Main Content -->
        <main id="main" class="main-content">
            <div class="container-fluid px-4 py-3">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->

    <script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>

    <script src="{{ asset('assets/js/main.js') }}"></script>
    @yield('scripts')
    @laravelViewsScripts
    <script>
         document.addEventListener('DOMContentLoaded', function() {
            // El navbar ya está configurado para mostrar/ocultar elementos según el nivel de acceso
            // No necesitamos lógica adicional aquí para el sidebar ya que usamos navbar
            
            // Ocultar cualquier elemento que contenga "LOGO" gigante
            const elements = document.querySelectorAll('*');
            elements.forEach(element => {
                if (element.textContent && element.textContent.trim() === 'LOGO' && element.tagName !== 'SCRIPT') {
                    element.style.display = 'none';
                }
            });
            
            // Inicializar todos los dropdowns de Bootstrap
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
            
            // Asegurar que los dropdowns funcionen correctamente
            document.querySelectorAll('.dropdown-toggle').forEach(function(dropdownToggle) {
                dropdownToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Cerrar otros dropdowns abiertos
                    document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
                        if (menu !== this.nextElementSibling) {
                            menu.classList.remove('show');
                        }
                    }.bind(this));
                    
                    // Toggle del dropdown actual
                    var dropdownMenu = this.nextElementSibling;
                    if (dropdownMenu) {
                        dropdownMenu.classList.toggle('show');
                    }
                });
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            var loader = document.getElementById('loadingOverlay');
            if (loader) {
                    loader.style.display = 'none';
            }
        });


        // function saveThemePreference(isDark) {
        //     $.ajax({
        //         url: '{{ route("saveThemePreference") }}',
        //         method: 'POST',
        //         data: {
        //             is_dark: isDark,
        //             _token: '{{ csrf_token() }}'
        //         },
        //         success: function(response) {
        //             console.log(response.message);
        //             // Cambiar dinámicamente el tema
        //             document.getElementById('body').classList.toggle('dark-mode', isDark);
        //         },
        //         error: function(error) {
        //             console.error('Error guardando la preferencia de tema:', error);
        //         }
        //     });
        // }

        // Evento para cambiar el tema
        // document.getElementById('toggleThemeButton').addEventListener('click', function() {
        //     var isDark = document.getElementById('body').classList.toggle('dark-mode');
        //     saveThemePreference(isDark);
        // });

        // function updateThemeIcon(isDark) {
        //     const themeIcon = document.getElementById('theme-icon');
        //     if (isDark) {
        //         themeIcon.classList.remove('bi-moon');
        //         themeIcon.classList.add('bi-brightness-high');
        //     } else {
        //         themeIcon.classList.remove('bi-brightness-high');
        //         themeIcon.classList.add('bi-moon');
        //     }
        // }

        // Evento para cambiar el tema
        // document.getElementById('light-dark-mode').addEventListener('click', function() {
        //     const body = document.getElementById('body');
        //     const isDark = body.classList.toggle('dark-mode');
        //     saveThemePreference(isDark);
        //     console.log(isDark)
        // });
        document.addEventListener("DOMContentLoaded", function() {
            const rows = document.querySelectorAll("tr.clickable-row");

            // Agregar evento de clic a las filas
            rows.forEach(row => {
                row.addEventListener("click", () => {
                    const href = row.dataset.href;
                    if (href) {
                        window.location.href = href;
                    }
                });
            });

            // Detener la propagación de los eventos de clic en los enlaces dentro de las filas
            const links = document.querySelectorAll("tr.clickable-row a");

            links.forEach(link => {
                link.addEventListener("click", (event) => {
                    event.stopPropagation(); // Detiene la propagación del evento
                });
            });

            // Si tienes botones o cualquier otro elemento interactivo, repite el proceso anterior para ellos
            const buttons = document.querySelectorAll("tr.clickable-row button");
            buttons.forEach(button => {
                button.addEventListener("click", (event) => {
                    event.stopPropagation();
                });
            });
        });
    </script>
</body>
</html>
