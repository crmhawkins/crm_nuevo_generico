@extends('layouts.appTpv')

@section('titulo', 'Dashboard TPV')

@section('css')
<link rel="stylesheet" href="{{asset('assets/vendors/choices.js/choices.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/css/Tpv.css')}}" />

<style>
    .navbar {
        margin: 0 !important;
        border-radius: 0px !important;
    }
    .sidebar-wrapper{
        margin: 0 !important;
        border-radius: 0px !important;
        height: 100vh;
    }
    #main{
        margin-left: 18rem ;
    }
    .mesa {
        width: 10vh; /* Tamaño del cuadrado */
        height: 10vh; /* Tamaño del cuadrado */
        background-color: #4CAF50; /* Color de fondo */
        color: white; /* Color del texto */
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px; /* Bordes redondeados */
        box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Sombra para un efecto 3D */
        font-weight: bold; /* Negrita para el texto */
        cursor: pointer; /* Cursor de movimiento */
        border: 2px solid #388E3C; /* Borde más oscuro para mejor definición */
    }


</style>
@endsection

@section('content')
<div class="page-heading card" style="box-shadow: none !important;" >
    <a  href="{{route('dashboard')}}" class="btn btn-success btn-lg" style="position: absolute; top: 10px; left: 10px; z-index: 100;" ><i class="fa-solid fa-chevron-left"></i></a>

    <div class="tpv-container d-flex" >
        <div id="mesas-container" class="flex-1" data-url="{{ url('tpv/mesa/') }}">

        </div>
    </div>
</div>

@endsection

@section('scripts')
@include('partials.toast')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
     $("#sidebar").css("display", "none");
     $("#main").css("margin-left", "0px");

    $(document).ready(function() {
        // Simulación de carga de mesas desde una base de datos
        const mesas = @json($mesas);

        loadMesas(mesas);
    });


    function loadMesas(mesas) {
        const $container = $('#mesas-container');
        const baseUrl = $container.data('url');
        const width = $container.width();
        const height = $container.height();

        const fragment = document.createDocumentFragment(); // Use a document fragment to avoid multiple reflows

        mesas.forEach(mesa => {
            const left = mesa.posicion_x * width / 100;
            const top = mesa.posicion_y * height / 100;
            const color = mesa.has_open_order ? 'blue' : '#4CAF50';

            const anchor = $('<a>').addClass('mesa')
                                .css({ position: 'absolute', left: `${left}px`, top: `${top}px`, 'background-color': color })
                                .attr('href', `${baseUrl}/${mesa.id}`)
                                .text(mesa.nombre);

            fragment.appendChild(anchor[0]); // Append to the fragment
        });

        $container.empty().append(fragment); // Append all at once
    }



</script>
@endsection
