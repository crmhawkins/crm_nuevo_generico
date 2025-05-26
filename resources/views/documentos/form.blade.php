<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group mb-4">
        <label class="mb-2 text-left uppercase" style="font-weight: bold" for="nombre">Nombre</label>
        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" value="{{ old('nombre', isset($documento) ? $documento->nombre : '') }}" name="nombre">
        @error('nombre')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group mb-4">
        <label class="mb-2 text-left uppercase" style="font-weight: bold" for="salon_id">Salon</label>
        <select class="form-select choices" id="salon_id" name="salon_id">
            <option value="">Seleccione salon</option>
            @foreach ($salones as $salon)
                <option value="{{ $salon->id }}" {{ old('salon_id',isset($documento) ? $documento->salon_id : '') == $salon->id ? 'selected' : '' }}>{{ $salon->nombre}}</option>
            @endforeach
        </select>
        @error('salon_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="form-group mb-4">
        <label class="mb-2 text-left uppercase" style="font-weight: bold" for="fecha">Fecha</label>
        <input type="date" class="form-control @error('fecha') is-invalid @enderror" id="fecha" value="{{ old('fecha', isset($documento) ? \Carbon\Carbon::parse($documento->fecha)->format('Y-m-d') : '') }}" name="fecha">
        @error('fecha')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="mb-4">
        <label for="archivo" class="mb-2 text-left uppercase" style="font-weight: bold">Archivo (PDF)</label>
        <input type="file" class="form-control" id="archivo" name="archivo" >
        @error('archivo')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        @if(isset($documento) && $documento->archivo)
            <div class="mt-2">
                <a href="{{ asset('storage/' . $documento->archivo) }}" target="_blank">Ver archivo actual</a>
            </div>
        @endif
    </div>

    {{-- Boton --}}
    <div class="form-group mt-5">
        <button type="submit" class="btn btn-success w-100 text-uppercase">
            {{ $buttonText }}
        </button>
    </div>
</form>
