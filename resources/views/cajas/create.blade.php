@extends('layouts.appTpv')

@section('titulo', 'Crear Recuento Cabina')

@section('css')
<link rel="stylesheet" href="{{asset('assets/vendors/choices.js/choices.min.css')}}" />
@endsection

@section('content')
 <div class="page-heading card" style="box-shadow: none !important" >
    <div class="page-title card-body">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Crear Recuento</h3>
                <p class="text-subtitle text-muted">Formulario para registrar un Recuento de cabina</p>
            </div>

            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Crear Recuento</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section mt-4">
        <div class="card">
            <div class="card-body">
                <form action="{{route('cabinas.store')}}" method="POST">
                    @csrf
                    <div class="bloque-formulario">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="mb-3 row">
                                    <div class="form-group col-lg-8 col-7">
                                        <label for="billetes_500">Billetes de 500€</label>
                                        <input placeholder="Recuento de billetes de 500" type="number" class="form-control @error('billetes_500') is-invalid @enderror" id="billetes_500" value="{{ old('billetes_500') }}" name="billetes_500">
                                        @error('billetes_500')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 col-5">
                                        <label for="billetes_100"></label>
                                        <input  class="form-control" id="cantidad_B_500" type="number" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="mb-3 row">
                                    <div class="form-group col-lg-8 col-7">
                                        <label for="billetes_200">Billetes de 200€</label>
                                        <input placeholder="Recuento de billetes de 200" type="number" class="form-control @error('billetes_200') is-invalid @enderror" id="billetes_200" value="{{ old('billetes_200') }}" name="billetes_200">
                                        @error('billetes_200')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 col-5">
                                        <label for="billetes_100"></label>
                                        <input  class="form-control" id="cantidad_B_200" type="number" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="mb-3 row">
                                    <div class="form-group col-lg-8 col-7">
                                        <label for="billetes_100">Billetes de 100€</label>
                                        <input placeholder="Recuento de billetes de 100" type="number" class="form-control @error('billetes_100') is-invalid @enderror" id="billetes_100" value="{{ old('billetes_100') }}" name="billetes_100">
                                        @error('billetes_100')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 col-5">
                                        <label for="billetes_100"></label>
                                        <input  class="form-control" id="cantidad_B_100" type="number" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="mb-3 row">
                                    <div class="form-group col-lg-8 col-7">
                                        <label for="billetes_50">Billetes de 50€</label>
                                        <input placeholder="Recuento de billetes de 50" type="number" class="form-control @error('billetes_50') is-invalid @enderror" id="billetes_50" value="{{ old('billetes_50') }}" name="billetes_50">
                                        @error('billetes_50')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 col-5">
                                        <label for="billetes_100"></label>
                                        <input  class="form-control" id="cantidad_B_50" type="number" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="mb-3 row">
                                    <div class="form-group col-lg-8 col-7">
                                        <label for="billetes_20">Billetes de 20€</label>
                                        <input placeholder="Recuento de billetes de 20" type="number" class="form-control @error('billetes_20') is-invalid @enderror" id="billetes_20" value="{{ old('billetes_20') }}" name="billetes_20">
                                        @error('billetes_20')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 col-5">
                                        <label for="billetes_100"></label>
                                        <input  class="form-control" id="cantidad_B_20" type="number" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="mb-3 row">
                                    <div class="form-group col-lg-8 col-7">
                                        <label for="billetes_10">Billetes de 10€</label>
                                        <input placeholder="Recuento de billetes de 10" type="number" class="form-control @error('billetes_10') is-invalid @enderror" id="billetes_10" value="{{ old('billetes_10') }}" name="billetes_10">
                                        @error('billetes_10')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 col-5">
                                        <label for="billetes_100"></label>
                                        <input  class="form-control" id="cantidad_B_10" type="number" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="mb-3 row">
                                    <div class="form-group col-lg-8 col-7">
                                        <label for="billetes_5">Billetes de 5€</label>
                                        <input placeholder="Recuento de billetes de 5" type="number" class="form-control @error('billetes_5') is-invalid @enderror" id="billetes_5" value="{{ old('billetes_5') }}" name="billetes_5">
                                        @error('billetes_5')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 col-5">
                                        <label for="billetes_100"></label>
                                        <input  class="form-control" id="cantidad_B_5" type="number" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="mb-3 row">
                                    <div class="form-group col-lg-8 col-7">
                                        <label for="monedas_200">Monedas de 2€</label>
                                        <input placeholder="Recuento de monedas de 2" type="number" class="form-control @error('monedas_200') is-invalid @enderror" id="monedas_200" value="{{ old('monedas_200') }}" name="monedas_200">
                                        @error('monedas_200')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 col-5">
                                        <label for="billetes_100"></label>
                                        <input  class="form-control" id="cantidad_M_200" type="number" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="mb-3 row">
                                    <div class="form-group col-lg-8 col-7">
                                        <label for="monedas_100">Monedas de 1€</label>
                                        <input placeholder="Recuento de monedas de 2" type="number" class="form-control @error('monedas_100') is-invalid @enderror" id="monedas_100" value="{{ old('monedas_100') }}" name="monedas_100">
                                        @error('monedas_100')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 col-5">
                                        <label for="billetes_100"></label>
                                        <input  class="form-control" id="cantidad_M_100" type="number" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="mb-3 row">
                                    <div class="form-group col-lg-8 col-7">
                                        <label for="monedas_50">Monedas de 0,50€</label>
                                        <input placeholder="Recuento de monedas de 2" type="number" class="form-control @error('monedas_50') is-invalid @enderror" id="monedas_50" value="{{ old('monedas_50') }}" name="monedas_50">
                                        @error('monedas_50')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 col-5">
                                        <label for="billetes_100"></label>
                                        <input  class="form-control" id="cantidad_M_50" type="number" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="mb-3 row">
                                    <div class="form-group col-lg-8 col-7">
                                        <label for="monedas_20">Monedas de 0.20€</label>
                                        <input placeholder="Recuento de monedas de 20 centimos" type="number" class="form-control @error('monedas_20') is-invalid @enderror" id="monedas_20" value="{{ old('monedas_20') }}" name="monedas_20">
                                        @error('monedas_20')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 col-5">
                                        <label for="billetes_100"></label>
                                        <input  class="form-control" id="cantidad_M_20" type="number" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="mb-3 row">
                                    <div class="form-group col-lg-8 col-7">
                                        <label for="monedas_10">Monedas de 0.10€</label>
                                        <input placeholder="Recuento de monedas de 2" type="number" class="form-control @error('monedas_10') is-invalid @enderror" id="monedas_10" value="{{ old('monedas_10') }}" name="monedas_10">
                                        @error('monedas_10')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 col-5">
                                        <label for="billetes_100"></label>
                                        <input  class="form-control" id="cantidad_M_10" type="number" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="mb-3 row">
                                    <div class="form-group col-lg-8 col-7">
                                        <label for="monedas_5">Monedas de 0.05€</label>
                                        <input placeholder="Recuento de monedas de 2" type="number" class="form-control @error('monedas_5') is-invalid @enderror" id="monedas_5" value="{{ old('monedas_5') }}" name="monedas_5">
                                        @error('monedas_5')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 col-5">
                                        <label for="billetes_100"></label>
                                        <input  class="form-control" id="cantidad_M_5" type="number" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="mb-3 row">
                                    <div class="form-group col-lg-8 col-7">
                                        <label for="monedas_2">Monedas de 0.02€</label>
                                        <input placeholder="Recuento de monedas de 2" type="number" class="form-control @error('monedas_2') is-invalid @enderror" id="monedas_2" value="{{ old('monedas_2') }}" name="monedas_2">
                                        @error('monedas_2')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 col-5">
                                        <label for="billetes_100"></label>
                                        <input  class="form-control" id="cantidad_M_2" type="number" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="mb-3 row">
                                    <div class="form-group col-lg-8 col-7">
                                        <label for="monedas_1">Monedas de 0.01€</label>
                                        <input placeholder="Recuento de monedas de 2" type="number" class="form-control @error('monedas_1') is-invalid @enderror" id="monedas_1" value="{{ old('monedas_1') }}" name="monedas_1">
                                        @error('monedas_1')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 col-5">
                                        <label for="billetes_100"></label>
                                        <input  class="form-control" id="cantidad_M_1" type="number" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="pin">Pin:</label>
                                    <input placeholder="Recuento de cabina" type="text" class="form-control @error('pin') is-invalid @enderror" id="pin" value="{{ old('pin') }}" name="pin">
                                    @error('pin')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="monto">Cantidad Total:</label>
                                    <input placeholder="Recuento de cabina" type="text" class="form-control @error('monto') is-invalid @enderror" id="monto" value="{{ old('monto') }}" name="monto" disabled>
                                    <input type="hidden" name="monto" id="monto_hidden">
                                    @error('monto')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-5">
                        <button type="submit" class="btn btn-success w-100 text-uppercase">
                            {{ __('Registrar') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

@endsection

@section('scripts')
<script src="{{asset('assets/vendors/choices.js/choices.min.js')}}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Mapeo de valores de billetes y monedas en euros
        const denominaciones = {
            "billetes_500": 500,
            "billetes_200": 200,
            "billetes_100": 100,
            "billetes_50": 50,
            "billetes_20": 20,
            "billetes_10": 10,
            "billetes_5": 5,
            "monedas_200": 2,
            "monedas_100": 1,
            "monedas_50": 0.5,
            "monedas_20": 0.2,
            "monedas_10": 0.1,
            "monedas_5": 0.05,
            "monedas_2": 0.02,
            "monedas_1": 0.01
        };

        // Función para actualizar los valores en euros y el total
        function actualizarValores() {
            let total = 0;

            for (const id in denominaciones) {
                const inputCantidad = document.getElementById(id);
                const inputResultado = document.getElementById("cantidad_" + id.replace("billetes_", "B_").replace("monedas_", "M_"));

                if (inputCantidad && inputResultado) {
                    let cantidad = parseInt(inputCantidad.value) || 0;
                    let valorTotal = cantidad * denominaciones[id];

                    inputResultado.value = valorTotal.toFixed(2); // Mostrar con dos decimales
                    total += valorTotal;
                }
            }

            // Actualizar el campo del total
            document.getElementById("monto").value = total.toFixed(2);
            document.getElementById("monto_hidden").value = total.toFixed(2);
        }

        // Asignar evento a cada input para recalcular valores cuando cambien
        for (const id in denominaciones) {
            const inputCantidad = document.getElementById(id);
            if (inputCantidad) {
                inputCantidad.addEventListener("input", actualizarValores);
            }
        }
    });
</script>
@endsection
