<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($department))
        @method('PUT')
    @endif
    
    <div class="form-group">
        <label class="form-label" for="name">
            <i class="fas fa-building me-2"></i>Nombre del Departamento
        </label>
        <input type="text" 
               class="form-control @error('name') is-invalid @enderror" 
               id="name" 
               name="name" 
               value="{{ old('name', isset($department) ? $department->name : '') }}"
               placeholder="Ingrese el nombre del departamento"
               required>
        @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    {{-- Botones --}}
    <div class="row mt-4">
        <div class="col-md-6">
            <a href="{{ route('departamento.index') }}" class="btn-modern btn-secondary-modern w-100">
                <i class="fas fa-arrow-left"></i>Cancelar
            </a>
        </div>
        <div class="col-md-6">
            <button type="submit" class="btn-modern btn-success-modern">
                <i class="fas fa-save"></i>{{ $buttonText }}
            </button>
        </div>
    </div>
</form>
