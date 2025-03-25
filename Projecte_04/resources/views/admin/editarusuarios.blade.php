<!-- filepath: c:\wamp64\www\Projecte_04\Projecte_04\resources\views\admin\editarusuarios.blade.php -->
@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')
<div class="admin-container">
    <h1>Editar Usuario</h1>

    <form action="{{ route('admin.usuarios.update', $usuario->id) }}" method="POST" class="form-punto">
        @csrf
        @method('PUT')
        <div>
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $usuario->nombre }}" required>
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ $usuario->email }}" required>
        </div>
        <div>
            <label for="password">Contraseña (opcional)</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>
        <div>
            <label for="password_confirmation">Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
        </div>
        <div>
            <label for="rol">Rol</label>
            <select name="rol" id="rol" class="form-control" required>
                <option value="usuario" {{ $usuario->rol == 'usuario' ? 'selected' : '' }}>Usuario</option>
                <option value="admin" {{ $usuario->rol == 'admin' ? 'selected' : '' }}>Administrador</option>
            </select>
        </div>
        <button type="submit" class="btn-submit-guardar">Actualizar</button>
        <a href="{{ route('admin.usuarios.index') }}" class="btn-submit-cancelar">Cancelar</a>
    </form>
</div>
@endsection