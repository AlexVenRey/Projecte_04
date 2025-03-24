<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Turismo App')</title>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        header {
            background-color: #333;
            color: white;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .logo-container img {
            height: 40px;
        }

        .user-name {
            font-size: 1.1rem;
            font-weight: bold;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 1rem;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        nav a:hover {
            background-color: #444;
        }

        main {
            min-height: calc(100vh - 100px);
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="{{ asset('img/logo.webp') }}" alt="Logo">
            @auth
                <span class="user-name">{{ Auth::user()->nombre }}</span>
            @endauth
        </div>
        <nav>
            <ul>
                @auth
                    @if(Auth::user()->rol === 'admin')
                        <li><a href="{{ route('admin.index') }}">Inicio</a></li>
                        <li><a href="{{ route('admin.puntos') }}">Puntos de Interés</a></li>
                        <li><a href="{{ route('admin.gimcana') }}">Gimcanas</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" style="background: none; border: none; color: white; cursor: pointer;">
                                    Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    @else
                        <li><a href="{{ route('cliente.index') }}">Inicio</a></li>
                        <li><a href="{{ route('cliente.favoritos') }}">Favoritos</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" style="background: none; border: none; color: white; cursor: pointer;">
                                    Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    @endif
                @else
                    <li><a href="{{ route('login') }}">Iniciar Sesión</a></li>
                    <li><a href="{{ route('register') }}">Registrarse</a></li>
                @endauth
            </ul>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    
    @yield('scripts')
</body>
</html>
