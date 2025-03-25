@extends('layouts.admin')

@section('title', 'Gestión de Lugares')

@section('header', 'Gestión de Lugares')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h6 class="m-0 font-weight-bold text-primary mb-2 mb-md-0">Lugares</h6>
            <a href="{{ route('admin.lugares.create') }}" class="btn btn-primary btn-responsive">
                <i class="fas fa-plus"></i> Nuevo Lugar
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Ubicación</th>
                        <th>Etiquetas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lugares as $lugar)
                    <tr>
                        <td data-label="Nombre">{{ $lugar->nombre }}</td>
                        <td data-label="Descripción">{{ $lugar->descripcion }}</td>
                        <td data-label="Ubicación">
                            <div class="d-flex flex-column">
                                <small>Lat: {{ number_format($lugar->latitud, 4) }}</small>
                                <small>Lon: {{ number_format($lugar->longitud, 4) }}</small>
                            </div>
                        </td>
                        <td data-label="Etiquetas">
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($lugar->etiquetas as $etiqueta)
                                    <span class="badge bg-primary">
                                        <i class="fas {{ $etiqueta->icono }}"></i>
                                        {{ $etiqueta->nombre }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td data-label="Acciones">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.lugares.edit', $lugar) }}" 
                                   class="btn btn-sm btn-primary"
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                    <span class="d-none d-md-inline ms-1">Editar</span>
                                </a>
                                <form action="{{ route('admin.lugares.destroy', $lugar) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('¿Estás seguro de que deseas eliminar este lugar?');">
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
            { responsivePriority: 2, targets: 3 },       // Etiquetas segunda prioridad
            { responsivePriority: 3, targets: 1 },       // Descripción tercera prioridad
            { responsivePriority: 4, targets: 2 }        // Ubicación última prioridad
        ]
    });
});
</script>
@endsection
