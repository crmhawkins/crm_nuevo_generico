<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group mb-4">
        <label class="mb-2 text-left uppercase" style="font-weight: bold" for="alta">Alta</label>
        <input type="date" class="form-control @error('alta') is-invalid @enderror" id="alta" value="{{ old('alta', isset($documento) ? \Carbon\Carbon::parse($documento->alta)->format('Y-m-d') : '') }}" name="alta">
        @error('alta')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group mb-4">
        <label class="mb-2 text-left uppercase" style="font-weight: bold" for="caducidad">Caducidad</label>
        <input type="date" class="form-control @error('caducidad') is-invalid @enderror" id="caducidad" value="{{ old('caducidad', isset($documento) ? \Carbon\Carbon::parse($documento->caducidad)->format('Y-m-d') : '') }}" name="caducidad">
        @error('caducidad')
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
        <label class="mb-2 text-left uppercase" style="font-weight: bold" for="nombre">Descipcion</label>
        <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion">{{ old('descripcion', isset($documento) ? $documento->descripcion : '') }}</textarea>
        @error('descripcion')
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
