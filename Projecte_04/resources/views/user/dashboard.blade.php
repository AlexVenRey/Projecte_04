@extends('layouts.app')

@section('title', 'Panel de Usuario')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Bienvenido, {{ Auth::user()->name }}</h5>
                    <p class="card-text">Aquí podrás ver los lugares turísticos y participar en gimcanas.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Mapa de Lugares</h5>
                </div>
                <div class="card-body">
                    <div id="map" style="height: 400px;"></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Filtrar por Etiquetas</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        @foreach($etiquetas as $etiqueta)
                            <div class="form-check">
                                <input class="form-check-input etiqueta-filter" type="checkbox" 
                                    value="{{ $etiqueta->id }}" id="etiqueta{{ $etiqueta->id }}">
                                <label class="form-check-label" for="etiqueta{{ $etiqueta->id }}">
                                    {{ $etiqueta->nombre }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Mis Gimcanas</h5>
                </div>
                <div class="card-body">
                    @if($grupos->count() > 0)
                        <div class="list-group">
                            @foreach($grupos as $grupo)
                                <a href="{{ route('user.gimcana.show', $grupo) }}" 
                                    class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $grupo->nombre }}</h6>
                                        <small>{{ $grupo->pruebas_completadas_count }}/{{ $grupo->pruebas_count }}</small>
                                    </div>
                                    <small class="text-muted">
                                        Progreso: {{ number_format(($grupo->pruebas_completadas_count / $grupo->pruebas_count) * 100, 0) }}%
                                    </small>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">No estás participando en ninguna gimcana.</p>
                    @endif
                </div>
            </div>
        </div>
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

    // Función para cargar lugares
    function loadPlaces(etiquetas = []) {
        fetch(`/api/lugares?etiquetas=${etiquetas.join(',')}`)
            .then(response => response.json())
            .then(data => {
                // Limpiar marcadores existentes
                map.eachLayer((layer) => {
                    if (layer instanceof L.Marker) {
                        map.removeLayer(layer);
                    }
                });

                // Añadir nuevos marcadores
                data.forEach(lugar => {
                    L.marker([lugar.latitud, lugar.longitud])
                        .bindPopup(`
                            <strong>${lugar.nombre}</strong><br>
                            ${lugar.descripcion}<br>
                            <small>${lugar.etiquetas.map(e => e.nombre).join(', ')}</small>
                        `)
                        .addTo(map);
                });
            });
    }

    // Evento para filtros de etiquetas
    document.querySelectorAll('.etiqueta-filter').forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const selectedEtiquetas = Array.from(document.querySelectorAll('.etiqueta-filter:checked'))
                .map(cb => cb.value);
            loadPlaces(selectedEtiquetas);
        });
    });

    // Cargar lugares iniciales
    loadPlaces();
});
</script>
@endsection
