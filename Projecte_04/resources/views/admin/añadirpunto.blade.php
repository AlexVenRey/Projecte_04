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
            <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre') }}">
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="latitud">Latitud</label>
                <input type="number" step="any" id="latitud" name="latitud" class="form-control" value="{{ old('latitud') }}">
            </div>

            <div class="form-group">
                <label for="longitud">Longitud</label>
                <input type="number" step="any" id="longitud" name="longitud" class="form-control" value="{{ old('longitud') }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="color_marcador">Color del marcador</label>
                <input type="color" id="color_marcador" name="color_marcador" value="#FF0000" >
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
            <a href="{{ route('admin.puntos') }}" class="btn-submit-cancelar">Cancelar</a>
        </div>
    </form>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('scripts')
<script src="{{ asset('js/añadirpunto.js') }}"></script>
@endsection