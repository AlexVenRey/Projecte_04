<!-- filepath: c:\wamp64\www\M12\Projecte_04\Projecte_04\resources\views\admin\editarpunto.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Punto de Interés</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
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
                    <li><a href="{{ url('admin/puntos') }}">Puntos de interés</a></li>
                    <li><a href="{{ url('admin/gimcana') }}">Gimcana</a></li>
                </ul>
            </nav>
        </header>
        <h1>Editar Punto de Interés</h1>
        <form action="{{ url('admin/puntos/' . $punto->id) }}" method="POST" enctype="multipart/form-data" class="form-punto">
            @csrf
            @method('PUT')
            <div>
                <label for="nombre">Nombre del sitio:</label>
                <input type="text" id="nombre" name="nombre" value="{{ $punto->nombre }}">
            </div>
            <div>
                <label for="latitud">Latitud:</label>
                <input type="text" id="latitud" name="latitud" value="{{ $punto->latitud }}">
            </div>
            <div>
                <label for="longitud">Longitud:</label>
                <input type="text" id="longitud" name="longitud" value="{{ $punto->longitud }}">
            </div>
            <div>
                <label for="descripcion">Pista:</label>
                <textarea id="descripcion" name="descripcion">{{ $punto->descripcion }}</textarea>
            </div>
            <div>
                <label for="icono">Icono:</label>
                <input type="file" id="icono" name="icono">
                <p>Icono actual: <img src="{{ asset('img/' . $punto->icono) }}" alt="Icono" style="width: 50px; height: 50px;"></p>
            </div>
            <div>
                <label for="etiquetas">Etiquetas:</label>
                <select id="etiquetas" name="etiquetas[]" multiple>
                    @foreach($etiquetas as $etiqueta)
                        <option value="{{ $etiqueta->id }}" {{ $punto->etiquetas->contains($etiqueta->id) ? 'selected' : '' }}>
                            {{ $etiqueta->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit">Guardar Cambios</button>
        </form>
    </div>
    <script src="{{ asset('js/editarpunto.js') }}"></script>
</body>
</html>