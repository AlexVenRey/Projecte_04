@extends('layouts.admin')

@section('title', 'Editar Prueba')

@section('header', 'Editar Prueba')

@section('content')
<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('admin.pruebas.update', $prueba) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                            id="titulo" name="titulo" value="{{ old('titulo', $prueba->titulo) }}" required>
                        @error('titulo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción de la Prueba</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                            id="descripcion" name="descripcion" rows="4" required>{{ old('descripcion', $prueba->descripcion) }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="pista" class="form-label">Pista</label>
                        <textarea class="form-control @error('pista') is-invalid @enderror" 
                            id="pista" name="pista" rows="3" required>{{ old('pista', $prueba->pista) }}</textarea>
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
                                    {{ (old('lugar_id', $prueba->lugar_id) == $lugar->id) ? 'selected' : '' }}>
                                    {{ $lugar->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('lugar_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="map" style="height: 300px;" class="mb-3"></div>

                    @if($prueba->grupos->count() > 0)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">Estado de los Grupos</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Grupo</th>
                                            <th>Estado</th>
                                            <th>Fecha Completada</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($prueba->grupos as $grupo)
                                        <tr>
                                            <td>{{ $grupo->nombre }}</td>
                                            <td>
                                                @if($grupo->pivot->completada)
                                                    <span class="badge bg-success">Completada</span>
                                                @else
                                                    <span class="badge bg-warning">Pendiente</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $grupo->pivot->completada ? $grupo->pivot->updated_at->format('d/m/Y H:i') : '-' }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="text-end">
                <a href="{{ route('admin.pruebas.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
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

    function updateMarker(lat, lng) {
        if (marker) {
            map.removeLayer(marker);
        }
        marker = L.marker([lat, lng]).addTo(map);
        map.setView([lat, lng], 16);
    }

    document.getElementById('lugar_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const lat = selectedOption.dataset.lat;
            const lng = selectedOption.dataset.lng;
            updateMarker(lat, lng);
        }
    });

    // Mostrar el marcador inicial
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
