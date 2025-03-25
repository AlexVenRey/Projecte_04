@extends('layouts.admin')

@section('title', 'Crear Lugar')

@section('header', 'Crear Nuevo Lugar')

@section('content')
<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('admin.lugares.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                            id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="latitud" class="form-label">Latitud</label>
                                <input type="number" step="any" class="form-control @error('latitud') is-invalid @enderror" 
                                    id="latitud" name="latitud" value="{{ old('latitud') }}" required>
                                @error('latitud')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="longitud" class="form-label">Longitud</label>
                                <input type="number" step="any" class="form-control @error('longitud') is-invalid @enderror" 
                                    id="longitud" name="longitud" value="{{ old('longitud') }}" required>
                                @error('longitud')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                            id="descripcion" name="descripcion" rows="3" required>{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="color_marcador" class="form-label">Color del Marcador</label>
                        <input type="color" class="form-control @error('color_marcador') is-invalid @enderror" 
                            id="color_marcador" name="color_marcador" value="{{ old('color_marcador', '#ff0000') }}" required>
                        @error('color_marcador')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="etiquetas" class="form-label">Etiquetas (selecciona al menos una)</label>
                        <select multiple class="form-control @error('etiquetas') is-invalid @enderror" 
                            id="etiquetas" name="etiquetas[]" required>
                            @foreach($etiquetas as $etiqueta)
                                <option value="{{ $etiqueta->id }}" 
                                    {{ in_array($etiqueta->id, old('etiquetas', [])) ? 'selected' : '' }}>
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

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('admin.lugares.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Inicializar Select2 para etiquetas
    $(document).ready(function() {
        $('#etiquetas').select2({
            placeholder: 'Selecciona las etiquetas',
            allowClear: true,
            templateResult: formatEtiqueta,
            templateSelection: formatEtiqueta
        });
    });

    // Función para formatear las opciones de etiquetas con iconos
    function formatEtiqueta(etiqueta) {
        if (!etiqueta.id) return etiqueta.text;
        return $('<span><i class="fas ' + $(etiqueta.element).find('i').attr('class') + '"></i> ' + etiqueta.text + '</span>');
    }
</script>
@endsection
