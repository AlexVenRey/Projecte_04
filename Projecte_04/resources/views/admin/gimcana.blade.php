<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gimcana</title>
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
                <div class="hamburger-menu" onclick="toggleMenu()">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
                <ul class="nav-links">
                    <li><a href="{{ url('admin/index') }}">Inicio</a></li>
                    <li><a href="{{ url('admin/puntos') }}">Puntos de interés</a></li>
                    <li><a href="{{ url('admin/gimcana') }}">Gimcana</a></li>
                    <li><a href="{{ url('admin/usuarios') }}">Usuarios</a></li>                        

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
                    <th>Lugares de Interés</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($gimcanas as $gimcana)
                    <tr>
                        <td>{{ $gimcana->nombre }}</td>
                        <td>{{ $gimcana->descripcion }}</td>
                        <td>
                            {{ $gimcana->lugares->pluck('nombre')->implode(', ') }}
                        </td>
                        <td>
                            <!-- Botón de Editar -->
                            <a href="{{ route('admin.gimcana.edit', $gimcana->id) }}" class="edit-btn">
                                <img src="{{ asset('img/editar.png') }}" alt="Editar">
                            </a>

                            <!-- Botón de Eliminar con llamada a la función confirmDelete() -->
                            <a href="javascript:void(0)" class="delete-btn" onclick="confirmDelete({{ $gimcana->id }})">
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

        function toggleMenu() {
            const navLinks = document.querySelector('.nav-links');
            navLinks.classList.toggle('active');
        }
    </script>
</body>
</html>
