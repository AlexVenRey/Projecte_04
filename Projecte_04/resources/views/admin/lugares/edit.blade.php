@extends('layouts.admin')

@section('title', 'Editar Lugar')

@section('header', 'Editar Lugar')

@section('content')
<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('admin.lugares.update', $lugar) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                            id="nombre" name="nombre" value="{{ old('nombre', $lugar->nombre) }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-2">
                        <div class="col-12 col-sm-6">
                            <div class="mb-3">
                                <label for="latitud" class="form-label">Latitud</label>
                                <input type="number" step="any" class="form-control @error('latitud') is-invalid @enderror" 
                                    id="latitud" name="latitud" value="{{ old('latitud', $lugar->latitud) }}" required>
                                @error('latitud')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="mb-3">
                                <label for="longitud" class="form-label">Longitud</label>
                                <input type="number" step="any" class="form-control @error('longitud') is-invalid @enderror" 
                                    id="longitud" name="longitud" value="{{ old('longitud', $lugar->longitud) }}" required>
                                @error('longitud')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                            id="descripcion" name="descripcion" rows="3" required>{{ old('descripcion', $lugar->descripcion) }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="color_marcador" class="form-label">Color del Marcador</label>
                        <input type="color" class="form-control form-control-color w-100 @error('color_marcador') is-invalid @enderror" 
                            id="color_marcador" name="color_marcador" value="{{ old('color_marcador', $lugar->color_marcador) }}" required>
                        @error('color_marcador')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="etiquetas" class="form-label">Etiquetas (selecciona al menos una)</label>
                        <select multiple class="form-control select2-tags @error('etiquetas') is-invalid @enderror" 
                            id="etiquetas" name="etiquetas[]" required>
                            @foreach($etiquetas as $etiqueta)
                                <option value="{{ $etiqueta->id }}" 
                                    {{ in_array($etiqueta->id, old('etiquetas', $lugar->etiquetas->pluck('id')->toArray())) ? 'selected' : '' }}>
                                    <i class="fas {{ $etiqueta->icono }}"></i> {{ $etiqueta->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('etiquetas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            El icono del lugar se determinará automáticamente según la etiqueta seleccionada.
                        </small>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex flex-wrap gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    <span class="d-none d-sm-inline ms-1">Guardar Cambios</span>
                </button>
                <a href="{{ route('admin.lugares.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    <span class="d-none d-sm-inline ms-1">Cancelar</span>
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Inicializar Select2 con soporte para iconos
        $('.select2-tags').select2({
            theme: 'bootstrap4',
            placeholder: 'Selecciona las etiquetas',
            allowClear: true,
            width: '100%',
            templateResult: formatEtiqueta,
            templateSelection: formatEtiqueta
        });

        // Función para formatear las opciones con iconos
        function formatEtiqueta(etiqueta) {
            if (!etiqueta.id) return etiqueta.text;
            var $etiqueta = $(
                '<span><i class="fas ' + $(etiqueta.element).find('i').attr('class') + '"></i> ' + 
                etiqueta.text + '</span>'
            );
            return $etiqueta;
        }

        // Ajustar Select2 en dispositivos móviles
        $('.select2-tags').on('select2:open', function() {
            if (window.innerWidth < 768) {
                $('.select2-search__field').focus();
            }
        });
    });
</script>
@endsection
