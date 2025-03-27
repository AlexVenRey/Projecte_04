<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Guía Turística</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/cliente.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css">
    <style>
        .leaflet-routing-container {
            max-height: 300px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-map-marked-alt"></i> Guía Turística
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" id="mostrarTodos" href="#">
                            <i class="fas fa-map"></i> Todos los lugares
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="mostrarFavoritos" href="#">
                            <i class="fas fa-heart"></i> Mis favoritos
                        </a>
                    </li>
                    @if(auth()->user()->rol === 'usuario')
                    <li class="nav-item">
                        <a href="{{ route('cliente.marcadores.create') }}" class="nav-link">
                            <i class="fas fa-plus-circle"></i> Crear marcador
                        </a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('cliente.gimcanas') }}" class="nav-link">
                            <i class="fas fa-trophy"></i> Gimcanas
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center">
                        <select class="form-select me-2" id="filtroEtiquetas">
                            <option value="">Todas las etiquetas</option>
                            @foreach($etiquetas as $etiqueta)
                                <option value="{{ $etiqueta->id }}">{{ $etiqueta->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex align-items-center">
                        <input type="number" 
                               class="form-control me-2" 
                               id="distancia" 
                               placeholder="Distancia (m)"
                               min="1"
                               step="100"
                               value="1000">
                        <button class="btn btn-outline-light" id="buscarCercanos">
                            <i class="fas fa-location-crosshairs"></i> Buscar cercanos
                        </button>
                    </div>
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

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div id="mapa" style="height: calc(100vh - 56px);"></div>
            </div>
        </div>
    </div>

    <!-- Modal de detalles -->
    <div class="modal fade" id="detallesModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btnFavorito">
                        <i class="fas fa-heart"></i> <span>Añadir a favoritos</span>
                    </button>
                    <button type="button" class="btn btn-primary" id="btnRuta">
                        <i class="fas fa-route"></i> Ver ruta
                    </button>
                    <button type="button" class="btn btn-danger d-none" id="btnEliminar">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para añadir un punto -->
    <div class="modal fade" id="addPointModal" tabindex="-1" aria-labelledby="addPointModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPointModalLabel">Añadir Punto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="addPointForm">
                        <div class="mb-3">
                            <label for="pointName" class="form-label">Nombre del Punto</label>
                            <input type="text" class="form-control" id="pointName" placeholder="Introduce un nombre">
                        </div>
                        <div class="mb-3">
                            <label for="pointLat" class="form-label">Latitud</label>
                            <input type="text" class="form-control" id="pointLat" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="pointLng" class="form-label">Longitud</label>
                            <input type="text" class="form-control" id="pointLng" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="pointTags" class="form-label">Etiquetas</label>
                            <select multiple class="form-select" id="pointTags">
                                @foreach($etiquetas as $etiqueta)
                                    <option value="{{ $etiqueta->id }}">{{ $etiqueta->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="pointColor" class="form-label">Color del Punto</label>
                            <input type="color" class="form-control form-control-color" id="pointColor" value="#FF0000">
                        </div>
                        <button type="button" class="btn btn-primary" id="savePoint">Guardar Punto</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <script src="{{ asset('js/cliente.js') }}"></script>
</body>
</html>