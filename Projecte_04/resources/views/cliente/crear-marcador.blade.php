@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-map-marker-alt"></i> Crear nuevo marcador
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('cliente.marcadores.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" name="descripcion" rows="3" required>{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="latitud" class="form-label">Latitud</label>
                                <input type="number" step="any" class="form-control @error('latitud') is-invalid @enderror" 
                                       id="latitud" name="latitud" value="{{ old('latitud') }}" required>
                                @error('latitud')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="longitud" class="form-label">Longitud</label>
                                <input type="number" step="any" class="form-control @error('longitud') is-invalid @enderror" 
                                       id="longitud" name="longitud" value="{{ old('longitud') }}" required>
                                @error('longitud')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="color_marcador" class="form-label">Color del marcador</label>
                            <input type="color" class="form-control form-control-color @error('color_marcador') is-invalid @enderror" 
                                   id="color_marcador" name="color_marcador" value="{{ old('color_marcador', '#ff0000') }}" required>
                            @error('color_marcador')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('cliente.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar marcador
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="mapa" style="height: 300px;" class="mt-4"></div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const map = L.map('mapa').setView([41.390205, 2.154007], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        let marker = null;

        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            
            document.getElementById('latitud').value = lat;
            document.getElementById('longitud').value = lng;
            
            if (marker) {
                map.removeLayer(marker);
            }
            
            marker = L.marker([lat, lng]).addTo(map);
        });
    });
</script>
@endpush
@endsection