@extends('layouts.admin')

@section('title', 'Nueva Prueba')

@section('header', 'Nueva Prueba')

@section('content')
<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('admin.pruebas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Prueba</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                            id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                            id="descripcion" name="descripcion" rows="3" required>{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo de Prueba</label>
                        <select class="form-select @error('tipo') is-invalid @enderror" id="tipo" name="tipo" required>
                            <option value="pregunta" {{ old('tipo') == 'pregunta' ? 'selected' : '' }}>
                                <i class="fas fa-question"></i> Pregunta
                            </option>
                            <option value="foto" {{ old('tipo') == 'foto' ? 'selected' : '' }}>
                                <i class="fas fa-camera"></i> Foto
                            </option>
                        </select>
                        @error('tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="row g-2">
                        <div class="col-12 col-sm-6">
                            <div class="mb-3">
                                <label for="puntos" class="form-label">Puntos</label>
                                <input type="number" class="form-control @error('puntos') is-invalid @enderror" 
                                    id="puntos" name="puntos" value="{{ old('puntos', 1) }}" required min="1">
                                @error('puntos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="mb-3">
                                <label for="orden" class="form-label">Orden</label>
                                <input type="number" class="form-control @error('orden') is-invalid @enderror" 
                                    id="orden" name="orden" value="{{ old('orden', 1) }}" required min="1">
                                @error('orden')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="lugar_id" class="form-label">Lugar</label>
                        <select class="form-select select2-lugares @error('lugar_id') is-invalid @enderror" 
                            id="lugar_id" name="lugar_id" required>
                            <option value="">Selecciona un lugar</option>
                            @foreach($lugares as $lugar)
                                <option value="{{ $lugar->id }}" {{ old('lugar_id') == $lugar->id ? 'selected' : '' }}>
                                    {{ $lugar->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('lugar_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="preguntaRespuesta" class="{{ old('tipo') == 'foto' ? 'd-none' : '' }}">
                        <div class="mb-3">
                            <label for="respuesta_correcta" class="form-label">Respuesta Correcta</label>
                            <input type="text" class="form-control @error('respuesta_correcta') is-invalid @enderror" 
                                id="respuesta_correcta" name="respuesta_correcta" value="{{ old('respuesta_correcta') }}">
                            @error('respuesta_correcta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex flex-wrap gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    <span class="d-none d-sm-inline ms-1">Crear Prueba</span>
                </button>
                <a href="{{ route('admin.pruebas.index') }}" class="btn btn-secondary">
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
    // Inicializar Select2 para lugares
    $('.select2-lugares').select2({
        theme: 'bootstrap4',
        placeholder: 'Selecciona un lugar',
        width: '100%'
    });

    // Manejar cambio de tipo de prueba
    $('#tipo').change(function() {
        if ($(this).val() === 'foto') {
            $('#preguntaRespuesta').addClass('d-none');
            $('#respuesta_correcta').prop('required', false);
        } else {
            $('#preguntaRespuesta').removeClass('d-none');
            $('#respuesta_correcta').prop('required', true);
        }
    });

    // Ajustar Select2 en dispositivos móviles
    $('.select2-lugares').on('select2:open', function() {
        if (window.innerWidth < 768) {
            $('.select2-search__field').focus();
        }
    });
});
</script>
@endsection
