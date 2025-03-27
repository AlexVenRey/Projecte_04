<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <title>CRUD Usuarios</title>
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

        <h1>Gestión de Usuarios</h1>
        <a href="{{ route('admin.usuarios.create') }}" class="add-btn">
            <img src="{{ asset('img/añadir.png') }}" alt="Añadir Usuario">
        </a>
    
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif
    
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios as $usuario)
                    <tr id="usuario-row-{{ $usuario->id }}">
                        <td>{{ $usuario->nombre }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ ucfirst($usuario->rol) }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" class="edit-btn">
                                    <img src="{{ asset('img/editar.png') }}" alt="Editar">
                                </a>
                                <button type="button" class="delete-btn" onclick="confirmDelete({{ $usuario->id }})">
                                    <img src="{{ asset('img/eliminar.png') }}" alt="Eliminar">
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
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
                // Realizar la solicitud de eliminación
                fetch(`{{ url('admin/usuarios') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        // Eliminar la fila de la tabla
                        document.getElementById(`usuario-row-${id}`).remove();
                        Swal.fire(
                            'Eliminado',
                            'El usuario ha sido eliminado correctamente.',
                            'success'
                        );
                    } else {
                        Swal.fire(
                            'Error',
                            'No se pudo eliminar el usuario.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    Swal.fire(
                        'Error',
                        'Ocurrió un error al intentar eliminar el usuario.',
                        'error'
                    );
                });
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
