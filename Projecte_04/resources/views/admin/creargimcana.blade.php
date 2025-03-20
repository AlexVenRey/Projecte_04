<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Gimcana</title>
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

        <h1>Crear Gimcana</h1>

        <form action="{{ route('admin.creargimcana.store') }}" method="POST">
            @csrf
            <div>
                <label for="nombre">Nombre de la Gimcana:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>

            <div>
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required></textarea>
            </div>

            <div>
                <label for="lugares">Seleccionar Puntos de Interés:</label>
                <select id="lugares" name="lugares[]" multiple required>
                    @foreach($lugares as $lugar)
                        <option value="{{ $lugar->id }}">{{ $lugar->nombre }}</option>
                    @endforeach
                </select>
                <p style="font-size: 12px;">Mantén presionada la tecla Ctrl (o Cmd en Mac) para seleccionar varios.</p>
            </div>

            <button type="submit">Crear Gimcana</button>
        </form>
    </div>
</body>
</html>
