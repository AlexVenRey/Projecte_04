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
<style>
.admin-container {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

h1 {
    color: #333;
    margin-bottom: 20px;
}

.add-btn {
    display: inline-block;
    margin-bottom: 20px;
}

.add-btn img {
    width: 40px;
    height: 40px;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #f5f5f5;
    font-weight: bold;
}

tr:hover {
    background-color: #f9f9f9;
}

.icon-preview {
    width: 32px;
    height: 32px;
    object-fit: contain;
}

.edit-btn, .delete-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
    margin: 0 5px;
}

.edit-btn img, .delete-btn img {
    width: 24px;
    height: 24px;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}
</style>
@endsection