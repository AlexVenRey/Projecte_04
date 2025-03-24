@extends('layouts.admin')

@section('title', 'Crear Prueba')

@section('header', 'Crear Nueva Prueba')

@section('content')
<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('admin.pruebas.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                            id="titulo" name="titulo" value="{{ old('titulo') }}" required>
                        @error('titulo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción de la Prueba</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                            id="descripcion" name="descripcion" rows="4" required>{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="pista" class="form-label">Pista</label>
                        <textarea class="form-control @error('pista') is-invalid @enderror" 
                            id="pista" name="pista" rows="3" required>{{ old('pista') }}</textarea>
                        <small class="form-text text-muted">Esta pista será mostrada a los participantes para ayudarles a encontrar el lugar.</small>
                        @error('pista')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="lugar_id" class="form-label">Lugar</label>
                        <select class="form-control @error('lugar_id') is-invalid @enderror" 
                            id="lugar_id" name="lugar_id" required>
                            <option value="">Selecciona un lugar</option>
                            @foreach($lugares as $lugar)
                                <option value="{{ $lugar->id }}" 
                                    data-lat="{{ $lugar->latitud }}" 
                                    data-lng="{{ $lugar->longitud }}"
                                    {{ old('lugar_id') == $lugar->id ? 'selected' : '' }}>
                                    {{ $lugar->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('lugar_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="map" style="height: 300px;" class="mb-3"></div>
                </div>
            </div>

            <div class="text-end">
                <a href="{{ route('admin.pruebas.index') }}" class="btn btn-secondary">Cancelar</a>
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

    // Función para actualizar el marcador
    function updateMarker(lat, lng) {
        if (marker) {
            map.removeLayer(marker);
        }
        marker = L.marker([lat, lng]).addTo(map);
        map.setView([lat, lng], 16);
    }

    // Evento change del select de lugar
    document.getElementById('lugar_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const lat = selectedOption.dataset.lat;
            const lng = selectedOption.dataset.lng;
            updateMarker(lat, lng);
        }
    });

    // Si hay un lugar seleccionado al cargar la página, mostrar su marcador
    const lugarSelect = document.getElementById('lugar_id');
    if (lugarSelect.value) {
        const selectedOption = lugarSelect.options[lugarSelect.selectedIndex];
        const lat = selectedOption.dataset.lat;
        const lng = selectedOption.dataset.lng;
        updateMarker(lat, lng);
    }
});
</script>
@endsection
