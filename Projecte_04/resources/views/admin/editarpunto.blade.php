<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="{{ asset('js/editarpunto.js') }}"></script>
    <title>Editar Punto de Interés</title>
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
                    <li><a href="{{ url('admin/gimcana') }}">Gimcana</a></li>
                    <li><a href="{{ url('admin/puntos') }}">Puntos de Interés</a></li>
                </ul>
            </nav>
        </header>

        <h1>Editar Punto de Interés</h1>
        
        @if($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    
        <form action="{{ route('admin.puntos.update', $punto->id) }}" method="POST" class="form-custom">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $punto->nombre) }}">
            </div>
    
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $punto->descripcion) }}</textarea>
            </div>
    
            <div class="form-row">
                <div class="form-group">
                    <label for="latitud">Latitud</label>
                    <input type="number" step="any" id="latitud" name="latitud" value="{{ old('latitud', $punto->latitud) }}">
                </div>
    
                <div class="form-group">
                    <label for="longitud">Longitud</label>
                    <input type="number" step="any" id="longitud" name="longitud" value="{{ old('longitud', $punto->longitud) }}">
                </div>
            </div>
    
            <div class="form-group">
                <label for="color_marcador">Color del marcador</label>
                <input type="color" id="color_marcador" name="color_marcador" value="{{ old('color_marcador', $punto->color_marcador) }}">
            </div>
    
            <div class="form-group">
                <label>Etiquetas</label>
                <div class="etiquetas-grid">
                    @foreach($etiquetas as $etiqueta)
                        <div class="etiqueta-item">
                            <input type="checkbox" name="etiquetas[]" value="{{ $etiqueta->id }}" 
                                   id="etiqueta{{ $etiqueta->id }}" {{ $punto->etiquetas->contains($etiqueta->id) ? 'checked' : '' }}>
                            <label for="etiqueta{{ $etiqueta->id }}">{{ $etiqueta->nombre }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
    
            <div class="form-actions">
                <button type="submit" class="btn-submit">Actualizar</button>
                <a href="{{ route('admin.puntos') }}" class="btn-submit-cancelar">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>