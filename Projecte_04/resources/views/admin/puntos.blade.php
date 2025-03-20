<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puntos de Interés</title>
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
                    <li><a href="{{ url('admin/gimcana') }}">Gimcana</a></li>
                </ul>
            </nav>
        </header>
        <h1>Puntos de Interés</h1>
        <a href="{{ url('admin/añadirpunto') }}" class="add-btn">
            <img src="{{ asset('img/añadir.png') }}" alt="Añadir">
        </a>
        <table>
            <thead>
                <tr>
                    <th>Nombre del sitio</th>
                    <th>Latitud</th>
                    <th>Longitud</th>
                    <th>Pista</th>
                    <th>Icono</th>
                    <th>Etiquetas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lugares as $lugar)
                <tr>
                    <td>{{ $lugar->nombre }}</td>
                    <td>{{ $lugar->latitud }}</td>
                    <td>{{ $lugar->longitud }}</td>
                    <td>{{ $lugar->descripcion }}</td>
                    <td><img src="{{ asset('img/' . $lugar->icono) }}" alt="Icono"></td>
                    <td>
                        @foreach($lugar->etiquetas as $etiqueta)
                            {{ $etiqueta->nombre }}
                        @endforeach
                    </td>
                    <td>
                        <button class="edit-btn">
                            <img src="{{ asset('img/editar.png') }}" alt="Editar">
                        </button>
                        <button class="delete-btn">
                            <img src="{{ asset('img/eliminar.png') }}" alt="Eliminar">
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>