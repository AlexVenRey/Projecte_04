@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Unirse a una Gimcana</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Introducir código de gimcana</h5>
            <form action="{{ route('cliente.gimcanas.unirse') }}" method="POST">
                @csrf
                <div class="form-group">
                    <input type="text" 
                           name="codigo" 
                           class="form-control @error('codigo') is-invalid @enderror" 
                           placeholder="Introduce el código de 6 caracteres"
                           maxlength="6"
                           required>
                    @error('codigo')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary mt-3">Unirse a la gimcana</button>
            </form>
        </div>
    </div>

    <h3>Gimcanas disponibles</h3>
    <div class="row">
        @forelse($gimcanas as $gimcana)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $gimcana->nombre }}</h5>
                        <p class="card-text">{{ $gimcana->descripcion }}</p>
                        <p class="card-text">
                            <small class="text-muted">
                                Participantes: {{ $gimcana->participantes_actuales }}/{{ $gimcana->max_participantes }}
                            </small>
                        </p>
                        <p class="card-text">
                            <small class="text-muted">
                                Código: {{ $gimcana->codigo_union }}
                            </small>
                        </p>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No hay gimcanas disponibles en este momento.
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        <a href="{{ route('cliente.gimcanas.mis-gimcanas') }}" class="btn btn-secondary">
            Ver mis gimcanas
        </a>
    </div>
</div>
@endsection
