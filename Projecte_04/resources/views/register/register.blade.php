<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="login-container">
        <!-- Contenedor del logo -->
        <div class="logo-container">
            <img src="{{ asset('img/logo.webp') }}" alt="Logo">
        </div>

        <!-- Contenedor del formulario -->
        <div class="form-container">
            <h2>Regístrate</h2>
            <form action="{{ url('/register') }}" method="POST">
                @csrf
                <input type="text" name="name" placeholder="Nombre completo" required>
                <input type="email" name="email" placeholder="Correo electrónico" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <input type="password" name="password_confirmation" placeholder="Confirmar contraseña" required>
                <button type="submit">Registrarse</button>
            </form>

            @if ($errors->any())
                <p class="error-message">{{ $errors->first() }}</p>
            @endif

            <!-- Enlace para volver al login -->
            <p class="register-text">¿Ya tienes cuenta? <a href="{{ url('/') }}">Inicia sesión</a></p>
        </div>
    </div>
</body>
</html>
