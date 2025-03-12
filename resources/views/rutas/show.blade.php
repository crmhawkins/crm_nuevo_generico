@extends('layouts.app')

@section('titulo', 'Ver Cliente')

@section('css')
    <link rel="stylesheet" href="{{asset('assets/vendors/toastify/toastify.css')}}">
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
@endsection

@section('content')

    <div class="page-heading card ">

        {{-- Titulos --}}
        <div class="page-title card-body">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <div class="d-flex">
                        <h2 class="ms-2 fs-2">{{$almacen->nombre}}</h2>
                    </div>
                    <p class="text-subtitle text-muted">{{$almacen->direccion}}</p>
                </div>

                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{route('almacenes.index')}}">Almacenes</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{$almacen->nombre}}</li>
                        </ol>
                    </nav>

                </div>
            </div>
        </div>

        <section class="section pt-4">
            <div class="card">
                <div class="card-body">

                    <div class="card">
                        <div class="card-content">
                            <div class="card-body" style="border:none">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="card mb-4">
                                          <div class="card-body text-center" style="border:none">
                                            <h5 class="my-3">{{$almacen->nombre}}</h5>
                                            <p class="text-muted mb-1">{{$almacen->direccion}}</p>
                                            <div class="row">
                                                <div class="col-6 text-right">
                                                    <p class="text-m$clientelist-presupuestos-listuted mb-4">{{$almacen->Apertura ? \Carbon\Carbon::parse($almacen->Apertura)->format('H:i') : ''}}</p>
                                                </div>
                                                <div class="col-6 text-left">
                                                    <p class="text-m$clientelist-presupuestos-listuted mb-4">{{$almacen->Cierre ? \Carbon\Carbon::parse($almacen->Cierre)->format('H:i') : ''}}</p>
                                                </div>
                                            </div>

                                          </div>
                                        </div>
                                        <div class="mb-4 list-group" role="tablist">
                                            <a class="list-group-item list-group-item-action active"
                                                id="list-maquina-list" data-bs-toggle="list" href="#list-maquina"
                                                role="tab">Maquinas</a>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-8 mt-1">
                                        <div class="tab-content text-justify" id="nav-tabContent">
                                            <div class="tab-pane show active" id="list-maquina" role="tabpanel"
                                                aria-labelledby="list-maquina-list">
                                                <h3 class="mb-2 fs-4 text-uppercase">Cajas del almacen</h3>
                                                <hr class="border mb-4" >
                                                @if (count($almacen->maquinas) > 0)
                                                    <div class="table-responsive">
                                                         <table class="table table-hover">
                                                            <thead class="header-table-other">
                                                                <th class="px-3" style="font-size:0.75rem">NOMBRE</th>
                                                                <th class="" style="font-size:0.75rem">Nº SERIE</th>
                                                                <th class="" style="font-size:0.75rem">CATEGORIA</th>
                                                                <th class="text-center" style="font-size:0.75rem">ACCIONES</th>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ( $almacen->maquinas as $maquina )
                                                                    <tr>
                                                                        <td>{{$maquina->nombre}} €</td>
                                                                        <td>{{$maquina->n_serie}} €</td>
                                                                        <td>{{optional($maquina->categoria)->nombre ?? ($maquina->categoria_id ? 'Categoria borrada' : 'Sin categoria asignada')}}</td>

                                                                        <td class="">
                                                                            <a class="" href="{{route('maquinas.edit', $maquina->id)}}"><img class="m-auto" src="{{asset('assets/icons/edit.svg')}}" alt="Mostrar usuario"></a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                <div class="text-center py-4">
                                                    <h3 class="text-center fs-4">No se encontraron registros de <strong>Maquinas</strong></h3>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @php
        $usuario = $almacen;
    @endphp
    @include('partials.modalAvatar')

@endsection

@section('scripts')
    <!-- toastify -->
    <script src="{{asset('assets/vendors/toastify/toastify.js')}}"></script>

    <!-- filepond -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Filepond: Basic
            FilePond.create( document.getElementById('inputFile'), {
                allowImagePreview: false,
                allowMultiple: false,
                allowFileEncode: false,
                required: false,
                storeAsFile: true,
                // server: '/user/avatar/1',
                // server: {
                //     process: async (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                //         console.log(options)
                //         console.log(transfer)
                //         console.log(fieldName)
                //         console.log(load)
                //         const id = 1;
                //         // const image = await toBase64(file)
                //         var frmdta = await new FormData();
                //         frmdta.append ( 'uploadfile', file);
                //         frmdta.append ( 'id', id);
                //         console.log(frmdta)
                //             addAvatar(frmdta)

                //     },
                // },
                server: {
                    process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                        // fieldName is the name of the input field
                        // file is the actual file object to send
                        const formData = new FormData();
                        formData.append(fieldName, file, file.name);
                        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                        const request = new XMLHttpRequest();
                        request.open('POST', '/user/avatar/1');

                        // Should call the progress method to update the progress to 100% before calling load
                        // Setting computable to false switches the loading indicator to infinite mode
                        request.upload.onprogress = (e) => {
                            progress(e.lengthComputable, e.loaded, e.total);
                        };

                        // Should call the load method when done and pass the returned server file id
                        // this server file id is then used later on when reverting or restoring a file
                        // so your server knows which file to return without exposing that info to the client
                        request.onload = function () {
                            if (request.status >= 200 && request.status < 300) {
                                // the load method accepts either a string (id) or an object
                                load(request.responseText);
                            } else {
                                // Can call the error method if something is wrong, should exit after
                                error('oh no');
                            }
                        };

                        request.send(formData);

                        $('#ModalAvatar'+{{$almacen->id}}).modal('hide');
                        location.reload();

                        // Should expose an abort method so the request can be cancelled
                        return {
                            abort: () => {
                                // This function is entered if the user has tapped the cancel button
                                request.abort();

                                // Let FilePond know the request has been cancelled
                                abort();
                            },
                        };
                    },
                }
            });
        })

    </script>
    @include('partials.toast')

    <script>
        function addAvatar(frmdta){
            console.log($('#inputFile'))
            $.when( postAvatar(frmdta) ).then(function( data, textStatus, jqXHR ) {
                // if (data.error) {
                //     Toast.fire({
                //         icon: "error",
                //         title: data.mensaje
                //     })
                // } else {
                //     Toast.fire({
                //         icon: "success",
                //         title: data.mensaje
                //     })

                //     setTimeout(() => {
                //         location.reload()
                //     }, 4000);
                // }
                console.log(jqXHR.status)
                const url ='/'+data
                const div = document.querySelector('.preview_image')
                $('.preview_image').attr("src", url);
                return jqXHR;
            });
        }
        function postAvatar(frmdta) {
            // console.log(await toBase64(image))
            return $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: frmdta

            });
        }
        const toBase64 = file => new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => resolve(reader.result);
            reader.onerror = reject;
        });
    </script>

@endsection
