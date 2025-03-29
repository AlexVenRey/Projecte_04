<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="gimcana-id" content="{{ $gimcana->id }}">
    <meta name="user-id" content="{{ Auth::id() }}">
    <title>Gimcana en Vivo</title>
    
    <!-- Estilos necesarios -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        #mapa {
            height: calc(100vh - 56px);
            width: 100%;
        }

        .panel-info {
            position: fixed;
            top: 70px;
            right: 20px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
            max-width: 300px;
        }

        .marcador-usuario {
            width: 20px;
            height: 20px;
            background-color: #2196F3;
            border: 2px solid white;
            border-radius: 50%;
            box-shadow: 0 0 4px rgba(0,0,0,0.3);
        }

        .marcador-punto {
            width: 30px;
            height: 30px;
            background-color: #FF5722;
            border: 2px solid white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .progreso-grupo {
            margin-top: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand">
                <i class="fas fa-map-marked-alt"></i> 
                Gimcana: {{ $gimcana->nombre }}
            </span>
        </div>
    </nav>

    <!-- Mapa -->
    <div id="mapa"></div>

    <!-- Panel de información -->
    <div class="panel-info">
        <h5>Grupo: <span id="nombre-grupo">Cargando...</span></h5>
        
        <div id="miembros-grupo" class="mb-3">
            <!-- Los miembros se cargarán dinámicamente -->
        </div>

        <div class="progreso-grupo">
            <h6>Progreso de la Gimcana</h6>
            <div class="progress mb-2">
                <div id="barra-progreso" class="progress-bar bg-success" 
                     role="progressbar" style="width: 0%">
                </div>
            </div>
            <small>
                Puntos visitados: 
                <span id="puntos-completados">0</span>/<span id="total-puntos">4</span>
            </small>
        </div>
    </div>

    <!-- Modal para pruebas -->
    <div class="modal fade" id="modalPrueba" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Resolver Prueba</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="descripcion-prueba"></p>
                    <div class="form-group">
                        <label>Tu respuesta:</label>
                        <input type="text" class="form-control" id="respuesta-prueba">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="verificarRespuesta()">
                        Enviar Respuesta
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts necesarios -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/live.js') }}"></script>
</body>
</html>
