@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Mis Gimcanas</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

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
                                Lugares a visitar: {{ $gimcana->lugares->count() }}
                            </small>
                        </p>
                        <button class="btn btn-primary" onclick="iniciarGimcana({{ $gimcana->id }})">
                            Iniciar Gimcana
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No estás participando en ninguna gimcana actualmente.
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        <a href="{{ route('cliente.gimcanas') }}" class="btn btn-secondary">
            Unirse a una gimcana
        </a>
    </div>
</div>

<script>
function iniciarGimcana(gimcanaId) {
    // Aquí puedes agregar la lógica para iniciar la gimcana
    alert('Iniciando gimcana ' + gimcanaId);
    // Implementaremos esta funcionalidad más adelante
}
</script>
@endsection
