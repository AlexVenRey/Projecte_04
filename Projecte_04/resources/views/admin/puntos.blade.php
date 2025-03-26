<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <title>Document</title>
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

        <h1>Puntos de Interés</h1>
        <a href="{{ route('admin.añadirpunto') }}" class="add-btn">
            <img src="{{ asset('img/añadir.png') }}" alt="Añadir">
        </a>
    
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif
    
        <table>
            <thead>
                <tr>
                    <th>Nombre del sitio</th>
                    <th>Latitud</th>
                    <th>Longitud</th>
                    <th>Pista</th>
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
                    <td>
                        {{ $lugar->etiquetas->pluck('nombre')->implode(', ') }}
                    </td>
                    <td>
                        <a href="{{ route('admin.puntos.edit', $lugar->id) }}" class="edit-btn">
                            <img src="{{ asset('img/editar.png') }}" alt="Editar">
                        </a>
                        <button type="button" class="delete-btn" onclick="confirmDelete({{ $lugar->id }})">
                            <img src="{{ asset('img/eliminar.png') }}" alt="Eliminar">
                        </button>
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