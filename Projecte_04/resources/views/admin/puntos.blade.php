<!-- filepath: c:\wamp64\www\M12\Projecte_04\Projecte_04\resources\views\admin\puntos.blade.php -->
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
                <span class="user-name">{{ Auth::user()->name }}</span>
            </div>
            <nav>
                <ul>
                    <li><a href="{{ url('admin/index') }}">Inicio</a></li>
                    <li><a href="{{ url('admin/puntos') }}">Puntos de interés</a></li>
                    <li><a href="{{ url('admin/etiquetas') }}">Etiquetas</a></li>
                </ul>
            </nav>
        </header>
        <h1>Puntos de Interés</h1>
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
                    <td>{{ $lugar->coordenadas->getLat() }}</td>
                    <td>{{ $lugar->coordenadas->getLng() }}</td>
                    <td>{{ $lugar->descripcion }}</td>
                    <td><img src="{{ asset('img/icons/' . $lugar->icono) }}" alt="Icono"></td>
                    <td>
                        @foreach($lugar->etiquetas as $etiqueta)
                            {{ $etiqueta->nombre }}
                        @endforeach
                    </td>
                    <td>
                        <button class="edit-btn">Editar</button>
                        <button class="delete-btn">Eliminar</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>