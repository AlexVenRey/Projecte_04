<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Gimcana</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="{{ asset('js/editargimcana.js') }}"></script>
</head>
<body>
    <div class="admin-container">
        <header>
            <div class="logo-container">
                <img src="{{ asset('img/logo.webp') }}" alt="Logo">
                <span class="user-name">{{ Auth::user()->nombre }}</span>
            </div>
            <nav>
                <ul>
                    <li><a href="{{ url('admin/index') }}">Inicio</a></li>
                    <li><a href="{{ url('admin/puntos') }}">Puntos de Interés</a></li>
                    <li><a href="{{ url('admin/gimcana') }}">Gimcana</a></li>
                </ul>
            </nav>
        </header>

        <h1>Editar Gimcana</h1>

        <!-- Mostrar errores de validación -->
        @if($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulario para editar gimcana -->
        <form action="{{ route('admin.gimcana.update', $gimcana->id) }}" method="POST" class="form-custom">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $gimcana->nombre) }}">
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $gimcana->descripcion) }}</textarea>
            </div>

            <div class="form-group">
                <label>Lugares de Interés</label>
                <div class="etiquetas-grid">
                    @foreach($lugares as $lugar)
                        <div class="etiqueta-item">
                            <input type="checkbox" name="lugares[]" value="{{ $lugar->id }}" 
                                   id="lugar{{ $lugar->id }}" {{ $gimcana->lugares->contains($lugar->id) ? 'checked' : '' }}>
                            <label for="lugar{{ $lugar->id }}">{{ $lugar->nombre }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">Guardar</button>
                <a href="{{ route('admin.gimcana') }}" class="btn-submit-cancelar">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
