<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Gimcana</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="{{ asset('js/creargimcana.js') }}"></script>
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
                <input type="text" id="nombre" name="nombre">
            </div>

            <div>
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion"></textarea>
            </div>

            <div>
                <label for="lugares">Selecciona los puntos de interés:</label>
                @foreach($lugares as $lugar)
                    <div style="display: flex; align-items: center; margin-right: 10px; margin-bottom: 5px;">
                        <input type="checkbox" id="lugar_{{ $lugar->id }}" name="lugares[]" value="{{ $lugar->id }}" style="margin-right: 5px;">
                        <label for="lugar_{{ $lugar->id }}" style="font-weight: normal;">{{ $lugar->nombre }}</label>
                    </div>
                @endforeach
            </div>
                                    
            <button type="submit">Crear Gimcana</button>
        </form>
    </div>
</body>
</html>
