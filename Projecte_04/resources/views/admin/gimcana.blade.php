<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <title>Gimcana</title>
</head>
<body>
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

    <h1>Pagina de la Gimcana</h1>
</body>
</html>