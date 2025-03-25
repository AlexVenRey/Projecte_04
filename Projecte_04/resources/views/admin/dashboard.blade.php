@extends('layouts.admin')

@section('title', 'Dashboard')

@section('header', 'Panel de Control')

@section('content')
<div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Lugares</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalLugares }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-map-marker-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Etiquetas</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalEtiquetas }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tags fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Pruebas Gimcana</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPruebas }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tasks fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Mapa de Lugares</h6>
            </div>
            <div class="card-body">
                <div id="map" style="height: 400px;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar el mapa
    var map = L.map('map').setView([41.3851, 2.1734], 13); // Barcelona como centro inicial

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Cargar los lugares mediante AJAX
    fetch('/api/admin/lugares')
        .then(response => response.json())
        .then(lugares => {
            lugares.forEach(lugar => {
                const marker = L.marker([lugar.latitud, lugar.longitud])
                    .bindPopup(`
                        <strong>${lugar.nombre}</strong><br>
                        ${lugar.direccion}<br>
                        <small>${lugar.etiquetas.map(e => e.nombre).join(', ')}</small>
                    `)
                    .addTo(map);
            });
        });
});
</script>
@endsection
