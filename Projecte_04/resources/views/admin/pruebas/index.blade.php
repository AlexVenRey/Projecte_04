@extends('layouts.admin')

@section('title', 'Gestión de Pruebas')

@section('header', 'Gestión de Pruebas')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h6 class="m-0 font-weight-bold text-primary mb-2 mb-md-0">Pruebas de Gincana</h6>
            <a href="{{ route('admin.pruebas.create') }}" class="btn btn-primary btn-responsive">
                <i class="fas fa-plus"></i> Nueva Prueba
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th data-mobile-label="Nom">Nombre</th>
                        <th data-mobile-label="Desc">Descripción</th>
                        <th data-mobile-label="Pts">Puntos</th>
                        <th data-mobile-label="Tipo">Tipo</th>
                        <th data-mobile-label="Acc">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pruebas as $prueba)
                    <tr>
                        <td data-label="Nombre">{{ $prueba->nombre }}</td>
                        <td data-label="Descripción">
                            <div class="text-truncate" style="max-width: 200px;">
                                {{ $prueba->descripcion }}
                            </div>
                        </td>
                        <td data-label="Puntos">{{ $prueba->puntos }}</td>
                        <td data-label="Tipo">
                            <span class="badge bg-{{ $prueba->tipo == 'pregunta' ? 'info' : 'warning' }}">
                                <i class="fas fa-{{ $prueba->tipo == 'pregunta' ? 'question' : 'camera' }}"></i>
                                {{ ucfirst($prueba->tipo) }}
                            </span>
                        </td>
                        <td data-label="Acciones">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.pruebas.edit', $prueba) }}" 
                                   class="btn btn-sm btn-primary"
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                    <span class="d-none d-md-inline ms-1">Editar</span>
                                </a>
                                <form action="{{ route('admin.pruebas.destroy', $prueba) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta prueba?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                        <span class="d-none d-md-inline ms-1">Eliminar</span>
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
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
        },
        columnDefs: [
            { responsivePriority: 1, targets: [0, -1] }, // Nombre y Acciones siempre visibles
            { responsivePriority: 2, targets: 2 },       // Puntos segunda prioridad
            { responsivePriority: 3, targets: 3 },       // Tipo tercera prioridad
            { responsivePriority: 4, targets: 1 }        // Descripción última prioridad
        ]
    });
});
</script>
@endsection
