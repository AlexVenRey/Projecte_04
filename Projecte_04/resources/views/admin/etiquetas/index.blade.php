@extends('layouts.admin')

@section('title', 'Gestión de Etiquetas')

@section('header', 'Gestión de Etiquetas')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.etiquetas.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nueva Etiqueta
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="etiquetasTable">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Lugares</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($etiquetas as $etiqueta)
                    <tr>
                        <td>{{ $etiqueta->nombre }}</td>
                        <td>{{ $etiqueta->descripcion }}</td>
                        <td>{{ $etiqueta->lugares_count }}</td>
                        <td>
                            <a href="{{ route('admin.etiquetas.edit', $etiqueta) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.etiquetas.destroy', $etiqueta) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#etiquetasTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
        }
    });
});
</script>
@endsection
