@extends('layouts.admin')

@section('title', 'Crear Lugar')

@section('header', 'Crear Nuevo Lugar')

@section('content')
<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('admin.lugares.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                            id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control @error('direccion') is-invalid @enderror" 
                            id="direccion" name="direccion" value="{{ old('direccion') }}" required>
                        @error('direccion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="latitud" class="form-label">Latitud</label>
                                <input type="number" step="any" class="form-control @error('latitud') is-invalid @enderror" 
                                    id="latitud" name="latitud" value="{{ old('latitud') }}" required>
                                @error('latitud')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="longitud" class="form-label">Longitud</label>
                                <input type="number" step="any" class="form-control @error('longitud') is-invalid @enderror" 
                                    id="longitud" name="longitud" value="{{ old('longitud') }}" required>
                                @error('longitud')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                            id="descripcion" name="descripcion" rows="3" required>{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="color" class="form-label">Color del Marcador</label>
                        <input type="color" class="form-control @error('color') is-invalid @enderror" 
                            id="color" name="color" value="{{ old('color', '#ff0000') }}">
                        @error('color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="icono" class="form-label">Icono</label>
                        <input type="file" class="form-control @error('icono') is-invalid @enderror" 
                            id="icono" name="icono" accept="image/*">
                        @error('icono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="etiquetas" class="form-label">Etiquetas</label>
                        <select multiple class="form-control @error('etiquetas') is-invalid @enderror" 
                            id="etiquetas" name="etiquetas[]">
                            @foreach($etiquetas as $etiqueta)
                                <option value="{{ $etiqueta->id }}" 
                                    {{ in_array($etiqueta->id, old('etiquetas', [])) ? 'selected' : '' }}>
                                    {{ $etiqueta->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('etiquetas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="map" style="height: 300px;" class="mb-3"></div>
                </div>
            </div>

            <div class="text-end">
                <a href="{{ route('admin.lugares.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('map').setView([41.3851, 2.1734], 13);
    var marker = null;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    map.on('click', function(e) {
        if (marker) {
            map.removeLayer(marker);
        }
        marker = L.marker(e.latlng).addTo(map);
        
        document.getElementById('latitud').value = e.latlng.lat;
        document.getElementById('longitud').value = e.latlng.lng;
    });

    // Geocodificación de la dirección
    document.getElementById('direccion').addEventListener('blur', function() {
        const direccion = this.value;
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(direccion)}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lon = parseFloat(data[0].lon);
                    
                    if (marker) {
                        map.removeLayer(marker);
                    }
                    marker = L.marker([lat, lon]).addTo(map);
                    map.setView([lat, lon], 16);
                    
                    document.getElementById('latitud').value = lat;
                    document.getElementById('longitud').value = lon;
                }
            });
    });
});
</script>
@endsection
