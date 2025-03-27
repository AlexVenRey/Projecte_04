<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <title>Crear Usuario</title>
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
                    <li><a href="{{ url('admin/puntos') }}">Puntos de Interés</a></li>
                    <li><a href="{{ url('admin/gimcana') }}">Gimcana</a></li>
                    <li><a href="{{ url('admin/usuarios') }}">Usuarios</a></li>
                </ul>
            </nav>
        </header>

        <h1>Crear Usuario</h1>
    
        <form action="{{ route('admin.usuarios.store') }}" method="POST" class="form-punto">
            @csrf
            <div>
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}" >
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" >
            </div>
            <div>
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" >
            </div>
            <div>
                <label for="password_confirmation">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" >
            </div>
            <div>
                <label for="rol">Rol</label>
                <select name="rol" id="rol" class="form-control" >
                    <option value="usuario" {{ old('rol') == 'usuario' ? 'selected' : '' }}>Usuario</option>
                    <option value="admin" {{ old('rol') == 'admin' ? 'selected' : '' }}>Administrador</option>
                </select>
            </div>
            <button type="submit" class="btn-submit-guardar">Guardar</button>
            <a href="{{ route('admin.usuarios.index') }}" class="btn-submit-cancelar">Cancelar</a>
        </form>
    </div>    

    <script src="{{ asset('js/usuario.js') }}"></script>
    <script>
        function toggleMenu() {
        const navLinks = document.querySelector('.nav-links');
        navLinks.classList.toggle('active');
    }
</script>
</body>
</html>