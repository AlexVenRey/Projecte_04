@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión de Etiquetas</h2>
        <a href="{{ route('admin.etiquetas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Etiqueta
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($etiquetas as $etiqueta)
                            <tr>
                                <td>{{ $etiqueta->id }}</td>
                                <td>{{ $etiqueta->nombre }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.etiquetas.edit', $etiqueta) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form action="{{ route('admin.etiquetas.destroy', $etiqueta) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta etiqueta?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
