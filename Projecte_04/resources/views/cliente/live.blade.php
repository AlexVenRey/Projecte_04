<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="gimcana-id" content="{{ $gimcana->id }}">
    <meta name="user-id" content="{{ Auth::id() }}">
    <title>Guía Turística - Live</title>
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
        
        /* Estilo para centrar el título en el navbar */
        .navbar-brand.mx-auto {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Ajuste para el mapa a pantalla completa */
        #mapa {
            height: calc(100vh - 56px);
            width: 100%;
        }

        /* Estilos para los marcadores de la gimcana */
        .marcador-gimcana {
            background: none;
            border: none;
        }

        .marcador {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            border: 2px solid white;
        }

        .numero {
            font-size: 18px;
        }

        /* Estilos para el modal de detalles */
        .modal-content {
            border-radius: 15px;
        }

        .modal-header {
            background-color: #f8f9fa;
            border-radius: 15px 15px 0 0;
        }

        .modal-footer {
            background-color: #f8f9fa;
            border-radius: 0 0 15px 15px;
        }

        /* Estilos para los botones */
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #545b62;
            border-color: #545b62;
        }

        .usuario-marker {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #ff4444;
            border: 2px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .lugar-siguiente {
            border: 3px solid #00ff00 !important;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        /* Estilos para el panel de información */
        #panel-grupo {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 300px;
            z-index: 1000;
        }

        .punto-control {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 5px;
            border-radius: 5px;
            background-color: #f8f9fa;
        }

        .punto-control.completado {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
        }

        .punto-control.actual {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            animation: pulse 2s infinite;
        }

        .punto-control-numero {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: #6c757d;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        .punto-control.completado .punto-control-numero {
            background-color: #28a745;
        }

        .punto-control.actual .punto-control-numero {
            background-color: #ffc107;
            color: #000;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand mx-auto" href="#">
                <i class="fas fa-map-marked-alt"></i> Guía Turística - {{ $gimcana->nombre }}
            </a>
        </div>
    </nav>

    <div id="mapa"></div>

    <!-- Panel de información del grupo -->
    <div id="panel-grupo" class="position-fixed top-0 end-0 m-3 p-3">
        <h5>Tu Grupo: <span id="nombre-grupo"></span></h5>
        <div id="miembros-grupo" class="mb-3"></div>
        
        <h6>Progreso de la Gimcana</h6>
        <div id="progreso-gimcana" class="mb-3">
            <div class="progress mb-2">
                <div id="barra-progreso" class="progress-bar" role="progressbar" style="width: 0%"></div>
            </div>
            <small>Puntos completados: <span id="lugares-completados">0</span>/<span id="total-lugares">0</span></small>
        </div>

        <div id="lista-puntos-control">
            <!-- Los puntos de control se cargarán dinámicamente -->
        </div>
    </div>

    <!-- Modal de detalles del punto -->
    <div class="modal fade" id="detallesModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Los detalles se cargarán dinámicamente -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btnRuta">
                        <i class="fas fa-route"></i> Ver ruta
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/live.js') }}"></script>
</body>
</html>
