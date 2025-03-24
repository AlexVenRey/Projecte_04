<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puntos de Interés</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        <!-- Botón para ir a la página de Crear Puntos -->
        <a href="{{ route('admin.añadirpunto') }}" class="add-btn">
            <img src="{{ asset('img/añadir.png') }}" alt="Añadir">
        </a>

        <!-- Mensaje de éxito -->
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tabla de Puntos de Interés -->
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
                        <td><img src="{{ asset('img/' . $lugar->icono) }}" alt="Icono" class="icon-preview"></td>
                        <td>{{ $lugar->etiquetas->pluck('nombre')->implode(', ') }}</td>
                        <td>
                            <!-- Botón de Editar -->
                            <a href="{{ route('admin.puntos.edit', $lugar->id) }}" class="edit-btn">
                                <img src="{{ asset('img/editar.png') }}" alt="Editar">
                            </a>

                            <!-- Botón de Eliminar con llamada a la función confirmDelete() -->
                            <button type="button" class="delete-btn" onclick="confirmDelete({{ $lugar->id }})">
                                <img src="{{ asset('img/eliminar.png') }}" alt="Eliminar">
                            </button>

                            <!-- Formulario oculto para eliminar el punto -->
                            <form id="delete-form-{{ $lugar->id }}" action="{{ route('admin.puntos.destroy', $lugar->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- SweetAlert Script -->
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esta acción!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Enviar el formulario de eliminación
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }
    </script>
</body>
</html>
