@extends('layouts.admin')

@section('title', 'Gestión de Lugares')

@section('header', 'Gestión de Lugares')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.lugares.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nuevo Lugar
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="lugaresTable">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Etiquetas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lugares as $lugar)
                    <tr>
                        <td>{{ $lugar->nombre }}</td>
                        <td>{{ $lugar->direccion }}</td>
                        <td>
                            @foreach($lugar->etiquetas as $etiqueta)
                                <span class="badge bg-primary">{{ $etiqueta->nombre }}</span>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('admin.lugares.edit', $lugar) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.lugares.destroy', $lugar) }}" method="POST" class="d-inline">
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
        <h6 class="m-0 font-weight-bold text-primary">Vista del Mapa</h6>
    </div>
    <div class="card-body">
        <div id="map" style="height: 400px;"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('map').setView([41.3851, 2.1734], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    @foreach($lugares as $lugar)
        L.marker([{{ $lugar->latitud }}, {{ $lugar->longitud }}])
            .bindPopup(`
                <strong>{{ $lugar->nombre }}</strong><br>
                {{ $lugar->direccion }}<br>
                <small>{{ $lugar->etiquetas->pluck('nombre')->implode(', ') }}</small>
            `)
            .addTo(map);
    @endforeach
});
</script>
@endsection
