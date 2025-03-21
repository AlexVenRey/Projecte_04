<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliente Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/cliente.css') }}">
</head>
<body onload="initMap()">
    <div class="cliente-container">
        <header>
            <div class="logo-container">
                <img src="{{ asset('img/logo.webp') }}" alt="Logo">
                <span class="user-name">{{ Auth::user()->nombre }}</span>
            </div>
            <nav>
                <ul>
                    <li><a href="{{ url('cliente/puntos') }}">Puntos de interés</a></li>
                    <li><a href="{{ url('cliente/gimcana') }}">Gimcana</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="logout-button">
                                <img src="{{ asset('img/cerrarsesion.png') }}" alt="Cerrar sesión" class="logout-icon">
                                Cerrar sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </header>
        <h1>Bienvenido al Dashboard Cliente</h1>
        <div id="map" style="height: 500px; width: 100%;"></div>
    </div>
</body>
</html>