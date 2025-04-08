<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group mb-4">
        <label class="mb-2 text-left uppercase" style="font-weight: bold" for="salon_id">Salon</label>
        <select class="form-control choices @error('salon_id') is-invalid @enderror" name="salon_id">
            <option value="">Seleccione un salon</option>
            @foreach ($salones as $salon)
                <option value="{{$salon->id}}" {{isset($nevera) && $nevera->salon_id == $salon->id ? 'selected' : ''}}>{{$salon->nombre}}</option>
            @endforeach
        </select>
        @error('salon_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="form-group mb-4">
        <label class="mb-2 text-left uppercase" style="font-weight: bold" for="nombre">Nombre</label>
        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" value="{{ old('nombre', isset($nevera) ? $nevera->nombre : '') }}" name="nombre">
        @error('nombre')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="form-group mb-4">
        <label class="mb-2 text-left uppercase" style="font-weight: bold" for="temperatura_actual">Temperatura Actual</label>
        <input type="number" step="0.01" class="form-control @error('temperatura_actual') is-invalid @enderror" id="temperatura_actual" value="{{ old('temperatura_actual', isset($nevera) ? $nevera->temperatura_actual : '') }}" name="temperatura_actual">
        @error('temperatura_actual')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group mb-4">
        <label class="mb-2 text-left uppercase" style="font-weight: bold" for="temperatura_maxima">Temperatura Maxima</label>
        <input type="number" step="0.01" class="form-control @error('temperatura_maxima') is-invalid @enderror" id="temperatura_maxima" value="{{ old('temperatura_maxima', isset($nevera) ? $nevera->temperatura_maxima : '') }}" name="temperatura_maxima">
        @error('temperatura_maxima')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group mb-4">
        <label class="mb-2 text-left uppercase" style="font-weight: bold" for="temperatura_minima">Temperatura Minima</label>
        <input type="number" step="0.01" class="form-control @error('temperatura_minima') is-invalid @enderror" id="temperatura_minima" value="{{ old('temperatura_minima', isset($nevera) ? $nevera->temperatura_minima : '') }}" name="temperatura_minima">
        @error('temperatura_minima')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group mb-4">
        <label class="mb-2 text-left uppercase" style="font-weight: bold" for="fecha">Fecha</label>
        <input type="date" class="form-control @error('fecha') is-invalid @enderror" id="fecha" value="{{ old('fecha', isset($nevera) ? \Carbon\Carbon::parse($nevera->fecha)->format('Y-m-d') : '') }}" name="fecha">
        @error('fecha')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group mb-4">
        <label class="mb-2 text-left uppercase" style="font-weight: bold" for="fecha">hora</label>
        <input type="time" class="form-control @error('hora') is-invalid @enderror" id="hora" value="{{ old('hora', isset($nevera) ? \Carbon\Carbon::parse($nevera->hora)->format('H:i:s') : '') }}" name="hora">
        @error('hora')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group mb-4">
        <label class="mb-2 text-left uppercase" style="font-weight: bold" for="fecha">Observaciones</label>
        <input type="text" class="form-control @error('observaciones') is-invalid @enderror" id="observaciones" value="{{ old('observaciones', isset($nevera) ? $nevera->observaciones : '') }}" name="observaciones">
        @error('observaciones')
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
