@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión de Gimcanas</h2>
        <a href="{{ route('admin.gimcanas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Gimcana
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
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Código de unión</th>
                            <th>Participantes</th>
                            <th>Lugares</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gimcanas as $gimcana)
                            <tr>
                                <td>{{ $gimcana->nombre }}</td>
                                <td>{{ Str::limit($gimcana->descripcion, 50) }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $gimcana->codigo_union }}</span>
                                </td>
                                <td>
                                    {{ $gimcana->participantes_actuales }}/{{ $gimcana->max_participantes }}
                                </td>
                                <td>{{ $gimcana->lugares->count() }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.gimcanas.edit', $gimcana) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.gimcanas.destroy', $gimcana) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar esta gimcana?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No hay gimcanas registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
