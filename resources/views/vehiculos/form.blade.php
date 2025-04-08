<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group mb-4">
        <label class="mb-2 text-left uppercase" style="font-weight: bold" for="matricula">Matricula</label>
        <input type="text" class="form-control @error('matricula') is-invalid @enderror" id="matricula" value="{{ old('matricula', isset($vehiculo) ? $vehiculo->matricula : '') }}" name="matricula">
        @error('matricula')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group mb-4">
        <label class="mb-2 text-left uppercase" style="font-weight: bold" for="fecha">Modelo</label>
        <input type="text" class="form-control @error('modelo') is-invalid @enderror" id="modelo" value="{{ old('modelo', isset($vehiculo) ? $vehiculo->modelo : '') }}" name="modelo">
        @error('modelo')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="form-group mb-4">
        <label class="mb-2 text-left uppercase" style="font-weight: bold" for="fecha">Fecha de compra</label>
        <input type="date" class="form-control @error('fecha') is-invalid @enderror" id="fecha_compra" value="{{ old('fecha_compra', isset($vehiculo) ? \Carbon\Carbon::parse($vehiculo->fecha_compra)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d')) }}" name="fecha_compra">
        @error('fecha')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group mb-4">
        <label class="mb-2 text-left uppercase" style="font-weight: bold" for="fecha">Fecha de itv</label>
        <input type="date" class="form-control @error('fecha') is-invalid @enderror" id="fecha_itv" value="{{ old('fecha_itv', isset($vehiculo) ? \Carbon\Carbon::parse($vehiculo->fecha_itv)->format('Y-m-d') : '') }}" name="fecha_itv">
        @error('fecha')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    {{-- Boton --}}
    <div class="form-group mt-5">
        <button type="submit" class="btn btn-success w-100 text-uppercase">
            {{ $buttonText }}
        </button>
    </div>
</form>
