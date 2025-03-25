<!-- filepath: c:\wamp64\www\Projecte_04\Projecte_04\resources\views\admin\usuarios.blade.php -->
@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')
<div class="admin-container">
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
                <tr>
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
                            <form id="delete-form-{{ $usuario->id }}" action="{{ route('admin.usuarios.destroy', $usuario->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
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
            document.getElementById(`delete-form-${id}`).submit();
        }
    });
}
</script>
@endsection