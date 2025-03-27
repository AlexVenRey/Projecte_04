<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand mx-auto" href="#">
                <i class="fas fa-map-marked-alt"></i> Guía Turística
            </a>
        </div>
    </nav>

    <div id="mapa"></div>

    <!-- Modal de detalles -->
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
    <script src="{{ asset('js/live.js') }}"></script>
</body>
</html>
