<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gimcana</title>
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
                </ul>
            </nav>
        </header>
        <h1>Gimcana</h1>

        <!-- Botón para ir a la página de Crear Gimcana -->
        <a href="{{ url('admin/creargimcana') }}" class="add-btn">
            <img src="{{ asset('img/añadir.png') }}" alt="Añadir Gimcana">
        </a>
    </div>
</body>
</html>
