@extends('layouts.app')

@section('titulo', 'Dashboard TPV')

@section('css')
<link rel="stylesheet" href="{{asset('assets/vendors/choices.js/choices.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/css/Tpv.css')}}" />

<style>


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
        cursor: move; /* Cursor de movimiento */
        border: 2px solid #388E3C; /* Borde más oscuro para mejor definición */
    }


</style>
@endsection

@section('content')
<div class="page-heading card" style="box-shadow: none !important;" >
    <a  href="{{route('salones.index')}}" class="btn btn-success btn-lg" style="position: absolute; top: 5px; left: 5px; z-index: 100;" ><i class="fa-solid fa-chevron-left"></i></a>

    <div class="tpv-container d-flex px-3 pt-12 pb-2" style="height: 82vh;border-radius:10px;">
        <button class="btn btn-success btn-lg" style="position: absolute; top: 5px; right: 5px; z-index: 100;" data-toggle="modal" data-target="#addMesaModal"><i class="fa-solid fa-plus"></i></button>
        <div id="mesas-container" class="flex-1">

        </div>
    </div>
</div>
<div class="modal fade" id="addMesaModal" tabindex="-1" role="dialog" aria-labelledby="addMesaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMesaModalLabel">Nueva Mesa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="add-mesa-form">
                    <div class="form-group">
                        <label for="mesa-nombre">Nombre de la Mesa</label>
                        <input type="text" class="form-control" id="mesa-nombre" placeholder="Ingrese nombre de la mesa">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="addMesa()" data-dismiss="modal">Guardar Mesa</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@include('partials.toast')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>

    $(document).ready(function() {
        // Simulación de carga de mesas desde una base de datos
        const mesas = @json($mesas);

        loadMesas(mesas);
    });

    function loadMesas(mesas) {
        $('#mesas-container').empty();
        const containerWidth = $('#mesas-container').width();
        const containerHeight = $('#mesas-container').height();

        mesas.forEach(function(mesa) {
            const left = mesa.posicion_x * containerWidth / 100;  // Calcula posición x basada en el porcentaje
            const top = mesa.posicion_y * containerHeight / 100; // Calcula posición y basada en el porcentaje
            $('#mesas-container').append(
                `<div class="mesa" id="mesa-${mesa.id}" style="position: absolute; left: ${left}px; top: ${top}px;">
                    ${mesa.nombre}
                    <button class="delete-mesa-btn" onclick="deleteMesa(${mesa.id})" style="margin-left: 5px; color: red; border: none; background: none;">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>`
            );
        });

        $('.mesa').draggable({
            containment: "parent",
            stop: function(event, ui) {
                const id = $(this).attr('id').split('-')[1];
                const pos = $(this).position();
                const containerWidth = $('#mesas-container').width();
                const containerHeight = $('#mesas-container').height();
                const xPercent = pos.left / containerWidth * 100;  // Convierte la posición a porcentaje
                const yPercent = pos.top / containerHeight * 100; // Convierte la posición a porcentaje

                var data = {
                    id: id,
                    posicion_x: xPercent,  // Envía posición como porcentaje
                    posicion_y: yPercent   // Envía posición como porcentaje
                };
                console.log(data);
                updateMesaPosition(data);
            }
        });
    }

    function addMesa() {
        var nombre = $('#mesa-nombre').val();
        var salon_id = @json($salonId);
       data={
        nombre:nombre,
        salon_id:salon_id
       }
       if(nombre && salon_id){
        fetch('/tpv/mesas/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            $('#mesa-nombre').val(''); // Limpia el campo de entrada después de añadir la mesa exitosamente
            getMesas();

        })
        .catch((error) => {
            console.error('Error:', error);
        });
       }
    }

    function getMesas(){
        var salon_id = @json($salonId);
        data={salon_id:salon_id }
        var mesas = @json($mesas);
        fetch('/tpv/mesas', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            loadMesas(data);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }

    function updateMesaPosition(data){
        fetch('/tpv/mesas/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            console.log('Success:', data);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }

    function deleteMesa(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esta acción!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/tpv/mesas/delete/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire(
                        'Eliminada!',
                        'La mesa ha sido eliminada.',
                        'success'
                    )
                    getMesas(); // Recarga las mesas después de eliminar
                })
                .catch((error) => {
                    console.error('Error:', error);
                    Swal.fire(
                        'Error!',
                        'No se pudo eliminar la mesa.',
                        'error'
                    )
                });
            }
        });
    }

</script>
@endsection
