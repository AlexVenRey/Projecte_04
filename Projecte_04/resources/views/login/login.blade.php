<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <script src="{{ asset('js/login.js') }}"></script>
</head>
<body>
    <div class="login-container">
        <!-- Contenedor del logo -->
        <div class="logo-container">
            <img src="{{ asset('img/logo.webp') }}" alt="Logo">
        </div>

        <!-- Contenedor del formulario -->
        <div class="form-container">
            <h2>Iniciar Sesión</h2>
            <form action="{{ url('/login') }}" method="POST">
                @csrf
                <input type="email" name="email" placeholder="Correo electrónico">
                <input type="password" name="password" placeholder="Contraseña">
                <button type="submit">Acceder</button>
            </form>

            @if ($errors->any())
                <p class="error-message">{{ $errors->first() }}</p>
            @endif

            <!-- Enlace de registro -->
            <p class="register-text">¿Aún no tienes cuenta? <a href="{{ url('/register') }}">Regístrate</a></p>
        </div>
    </div>
</body>
</html>
