@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')
<div class="admin-container">
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
</script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection