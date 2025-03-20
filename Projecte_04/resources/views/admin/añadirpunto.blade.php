<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Punto de Interés</title>
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
        <h1>Añadir Punto de Interés</h1>
        <form action="{{ url('admin/puntos') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="nombre">Nombre del sitio:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div>
                <label for="latitud">Latitud:</label>
                <input type="text" id="latitud" name="latitud" required>
            </div>
            <div>
                <label for="longitud">Longitud:</label>
                <input type="text" id="longitud" name="longitud" required>
            </div>
            <div>
                <label for="descripcion">Pista:</label>
                <textarea id="descripcion" name="descripcion" required></textarea>
            </div>
            <div>
                <label for="icono">Icono:</label>
                <input type="file" id="icono" name="icono" required>
            </div>
            <div>
                <label for="etiquetas">Etiquetas:</label>
                <select id="etiquetas" name="etiquetas[]" multiple required>
                    @foreach($etiquetas as $etiqueta)
                        <option value="{{ $etiqueta->id }}">{{ $etiqueta->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit">Añadir Punto</button>
        </form>
    </div>
</body>
</html>