@extends('layouts.admin')

@section('title', 'Editar Etiqueta')

@section('header', 'Editar Etiqueta')

@section('content')
<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('admin.etiquetas.update', $etiqueta) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                    id="nombre" name="nombre" value="{{ old('nombre', $etiqueta->nombre) }}" required>
                @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                    id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $etiqueta->descripcion) }}</textarea>
                @error('descripcion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="text-end">
                <a href="{{ route('admin.etiquetas.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection
