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
        <h1>Gimcanas</h1>

        <!-- Botón para ir a la página de Crear Gimcana -->
        <a href="{{ url('admin/creargimcana') }}" class="add-btn">
            <img src="{{ asset('img/añadir.png') }}" alt="Añadir Gimcana">
        </a>

        <!-- Tabla de Gimcanas -->
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Lugares de Interés</th> <!-- Nueva columna de lugares -->
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($gimcanas as $gimcana)
                    <tr>
                        <td>{{ $gimcana->nombre }}</td>
                        <td>{{ $gimcana->descripcion }}</td>
                        <td>
                            <!-- Mostrar los lugares asociados -->
                            @foreach ($gimcana->lugares as $lugar)
                                <span>{{ $lugar->nombre }}</span>
                                @if (!$loop->last), @endif <!-- Para agregar una coma entre los lugares, menos en el último -->
                            @endforeach
                        </td>
                        <td>
                            <!-- Botón de Editar -->
                            <a href="{{ route('admin.gimcana.edit', $gimcana->id) }}" class="edit-btn">
                                <img src="{{ asset('img/editar.png') }}" alt="Editar">
                            </a>

                            <!-- Botón de Eliminar -->
                            <a href="{{ route('admin.gimcana.delete', $gimcana->id) }}" class="delete-btn" onclick="event.preventDefault(); if (confirm('¿Estás seguro de que deseas eliminar esta gimcana?')) document.getElementById('delete-form-{{ $gimcana->id }}').submit();">
                                <img src="{{ asset('img/eliminar.png') }}" alt="Eliminar">
                            </a>

                            <!-- Formulario oculto para eliminar la gimcana -->
                            <form id="delete-form-{{ $gimcana->id }}" action="{{ route('admin.gimcana.delete', $gimcana->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
