<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <script src="{{ asset('js/register.js') }}"></script>
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
            <form action="{{ route('register.store') }}" method="POST">
                @csrf
                <input type="text" name="nombre" placeholder="Nombre completo" value="{{ old('nombre') }}">
                @if ($errors->has('nombre'))
                    <span class="error-message">{{ $errors->first('nombre') }}</span>
                @endif

                <input type="email" name="email" placeholder="Correo electrónico" value="{{ old('email') }}">
                @if ($errors->has('email'))
                    <span class="error-message">{{ $errors->first('email') }}</span>
                @endif

                <input type="password" name="password" placeholder="Contraseña">
                @if ($errors->has('password'))
                    <span class="error-message">{{ $errors->first('password') }}</span>
                @endif

                <input type="password" name="password_confirmation" placeholder="Confirmar contraseña">
                @if ($errors->has('password_confirmation'))
                    <span class="error-message">{{ $errors->first('password_confirmation') }}</span>
                @endif

                <button type="submit">Registrarse</button>
            </form>

            <!-- Enlace para volver al login -->
            <p class="register-text">¿Ya tienes cuenta? <a href="{{ url('/') }}">Inicia sesión</a></p>
        </div>
    </div>
</body>
</html>
