@extends('layouts.admin')

@section('title', 'Gestión de Pruebas Gimcana')

@section('header', 'Gestión de Pruebas Gimcana')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.pruebas.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nueva Prueba
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="pruebasTable">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Lugar</th>
                        <th>Descripción</th>
                        <th>Pista</th>
                        <th>Grupos Completados</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pruebas as $prueba)
                    <tr>
                        <td>{{ $prueba->titulo }}</td>
                        <td>{{ $prueba->lugar->nombre }}</td>
                        <td>{{ Str::limit($prueba->descripcion, 100) }}</td>
                        <td>{{ Str::limit($prueba->pista, 100) }}</td>
                        <td>
                            {{ $prueba->grupos->where('pivot.completada', true)->count() }}
                            de
                            {{ $prueba->grupos->count() }}
                        </td>
                        <td>
                            <a href="{{ route('admin.pruebas.edit', $prueba) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.pruebas.destroy', $prueba) }}" method="POST" class="d-inline">
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

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Mapa de Pruebas</h6>
    </div>
    <div class="card-body">
        <div id="map" style="height: 400px;"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar DataTable
    $('#pruebasTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
        }
    });

    // Inicializar mapa
    var map = L.map('map').setView([41.3851, 2.1734], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Añadir marcadores para cada prueba
    @foreach($pruebas as $prueba)
        L.marker([{{ $prueba->lugar->latitud }}, {{ $prueba->lugar->longitud }}])
            .bindPopup(`
                <strong>{{ $prueba->titulo }}</strong><br>
                Lugar: {{ $prueba->lugar->nombre }}<br>
                <small>{{ Str::limit($prueba->descripcion, 100) }}</small>
            `)
            .addTo(map);
    @endforeach
});
</script>
@endsection
