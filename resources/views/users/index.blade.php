@extends('layouts.app')

@section('titulo', 'Usuarios')

@section('content')

<div class="page-heading card" style="box-shadow:none!important">

    <div class="page-title card-body">
        <div>
            <h3><i class="bi bi-people-fill"></i> Usuarios</h3>
            <p class="text-subtitle text-muted">Gestión de usuarios</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Usuarios</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                @livewire('users-table')
            </div>
        </div>
    </section>

</div>

@endsection

@section('scripts')
    @include('partials.toast')

    <script>
        function botonAceptar(id){
            $.when( getDelete(id) ).then(function( data, textStatus, jqXHR ) {
                if (data.error) {
                    Toast.fire({
                        icon: "error",
                        title: data.mensaje
                    })
                } else {
                    Toast.fire({
                        icon: "success",
                        title: data.mensaje
                    })

                    setTimeout(() => {
                        location.reload()
                    }, 4000);
                }
            });
        }
        function getDelete(id) {
            const url = '{{route("users.delete")}}'
            return $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: {
                    'id': id,
                },
                dataType: "json"
            });
        }
    </script>
@endsection
