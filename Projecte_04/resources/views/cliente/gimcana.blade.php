<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gimcanas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/cliente.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="fas fa-map-marked-alt"></i> Guía Turística</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a href="{{ route('cliente.gimcanas') }}" class="nav-link active">
                            <i class="fas fa-trophy"></i> Gimcanas
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('cliente.index') }}" class="btn btn-secondary">Volver a la Guía Turística</a>
                    <form action="{{ route('logout') }}" method="POST" class="ms-2">
                        @csrf
                        <button type="submit" class="btn btn-dark">
                            <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Lista de Gimcanas</h1>
        <ul class="list-group">
            @foreach($gimcanas as $gimcana)
                <li class="list-group-item" data-bs-toggle="modal" data-bs-target="#grupoModal{{ $gimcana->id }}">
                    <h5>{{ $gimcana->nombre }}</h5>
                    <p>{{ $gimcana->descripcion }}</p>
                </li>

                <!-- Modal para ver grupos y unirse -->
                <div class="modal fade" id="grupoModal{{ $gimcana->id }}" tabindex="-1" aria-labelledby="grupoModalLabel{{ $gimcana->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="grupoModalLabel{{ $gimcana->id }}">Grupos en {{ $gimcana->nombre }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                <ul class="list-group lista-grupos">
                                    @foreach($gimcana->grupos as $grupo)
                                        <li class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6>{{ $grupo->nombre }}</h6>
                                                    <div class="miembros-grupo">
                                                        @foreach($grupo->usuarios as $usuario)
                                                            <span class="badge bg-secondary me-1">{{ $usuario->nombre }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <button class="btn btn-primary" onclick="unirseGrupo({{ $grupo->id }}, {{ $gimcana->id }})">
                                                    Unirse al Grupo
                                                </button>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <button class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#crearGrupoModal{{ $gimcana->id }}">
                                    Crear tu propio grupo
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal para crear un nuevo grupo -->
                <div class="modal fade" id="crearGrupoModal{{ $gimcana->id }}" tabindex="-1" aria-labelledby="crearGrupoModalLabel{{ $gimcana->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="crearGrupoModalLabel{{ $gimcana->id }}">Crear un nuevo grupo</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nombreGrupo{{ $gimcana->id }}" class="form-label">Nombre del Grupo</label>
                                    <input type="text" class="form-control" id="nombreGrupo{{ $gimcana->id }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="button" class="btn btn-primary" onclick="crearGrupo({{ $gimcana->id }})">
                                    Crear Grupo
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </ul>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/gimcanascliente.js') }}"></script>
</body>
</html>
