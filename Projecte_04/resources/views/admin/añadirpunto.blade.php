@extends('layouts.app')

@section('content')
<div class="admin-container">
    <h1>Añadir Punto de Interés</h1>
    
    @if($errors->any())
        <div class="alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.puntos.store') }}" method="POST" enctype="multipart/form-data" class="form-custom">
        @csrf
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" required value="{{ old('nombre') }}">
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" rows="3" required>{{ old('descripcion') }}</textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="latitud">Latitud</label>
                <input type="number" step="any" id="latitud" name="latitud" required value="{{ old('latitud') }}">
            </div>

            <div class="form-group">
                <label for="longitud">Longitud</label>
                <input type="number" step="any" id="longitud" name="longitud" required value="{{ old('longitud') }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="icono">Icono (imagen)</label>
                <input type="file" id="icono" name="icono" accept="image/*" required>
                <div id="preview-container" class="preview-container" style="display: none;">
                    <img id="preview-image" src="#" alt="Vista previa">
                </div>
            </div>

            <div class="form-group">
                <label for="color_marcador">Color del marcador</label>
                <input type="color" id="color_marcador" name="color_marcador" value="#FF0000" required>
            </div>
        </div>

        <div class="form-group">
            <label>Etiquetas</label>
            <div class="etiquetas-grid">
                @foreach($etiquetas as $etiqueta)
                    <div class="etiqueta-item">
                        <input type="checkbox" name="etiquetas[]" value="{{ $etiqueta->id }}" 
                               id="etiqueta{{ $etiqueta->id }}" {{ in_array($etiqueta->id, old('etiquetas', [])) ? 'checked' : '' }}>
                        <label for="etiqueta{{ $etiqueta->id }}">{{ $etiqueta->nombre }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Guardar</button>
            <a href="{{ route('admin.puntos') }}" class="btn-cancel">Cancelar</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const iconoInput = document.getElementById('icono');
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('preview-image');

    iconoInput.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
});
</script>

@endsection

@section('styles')
<style>
.admin-container {
    padding: 20px;
    max-width: 800px;
    margin: 0 auto;
}

.form-custom {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 20px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
    color: #333;
    font-weight: bold;
}

input[type="text"],
input[type="number"],
textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

input[type="file"] {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: white;
}

input[type="color"] {
    width: 100%;
    height: 40px;
    padding: 2px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.preview-container {
    margin-top: 10px;
    padding: 10px;
    border: 1px dashed #ddd;
    border-radius: 4px;
}

.preview-container img {
    max-width: 100px;
    max-height: 100px;
    object-fit: contain;
}

.etiquetas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 10px;
    margin-top: 10px;
}

.etiqueta-item {
    display: flex;
    align-items: center;
    gap: 5px;
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.btn-submit, .btn-cancel {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    text-decoration: none;
    text-align: center;
}

.btn-submit {
    background-color: #4CAF50;
    color: white;
}

.btn-cancel {
    background-color: #f44336;
    color: white;
}

.btn-submit:hover {
    background-color: #45a049;
}

.btn-cancel:hover {
    background-color: #da190b;
}

.alert-error {
    background-color: #ffebee;
    border: 1px solid #ffcdd2;
    color: #c62828;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.alert-error ul {
    margin: 0;
    padding-left: 20px;
}

textarea {
    resize: vertical;
    min-height: 100px;
}
</style>
@endsection