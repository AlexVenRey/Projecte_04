<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="gimcana-id" content="{{ $gimcana->id }}">
    <meta name="user-id" content="{{ Auth::id() }}">
    <title>Gimcana en Vivo</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        #mapa {
            height: calc(100dvh - 56px);
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

        @media (max-width: 767px) {
            #mapa {
                height: calc(100dvh - 110px);
            }

            .panel-info {
                display: none;
            }

            .navbar-brand {
                max-width: 60vw;
                font-size: 0.9rem;
            }

            .marcador-punto {
                width: 25px;
                height: 25px;
                font-size: 0.8rem;
            }
        }

        .movil-panel {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 12px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .swal-movil .swal2-popup {
            width: 90% !important;
            max-width: 100%;
        }

        .btn-tactil {
            min-width: 44px;
            min-height: 44px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark py-2">
        <div class="container-fluid">
            <span class="navbar-brand">
                <i class="fas fa-map-marked-alt me-2"></i>
                {{ Str::limit($gimcana->nombre, 25) }}
            </span>
            <div class="text-white d-md-none">
                <span id="puntos-completados">0</span>/<span id="total-puntos">0</span>
            </div>
        </div>
    </nav>

    <div id="mapa"></div>

    <div class="panel-info d-none d-md-block">
        <h5>Grupo: <span id="nombre-grupo">Cargando...</span></h5>
        <div id="miembros-grupo" class="mb-3"></div>
        <div class="progreso-grupo">
            <h6>Progreso</h6>
            <div class="progress mb-2">
                <div id="barra-progreso" class="progress-bar bg-success" 
                     role="progressbar" style="width: 0%"></div>
            </div>
            <small>Puntos: <span id="puntos-completados-desktop">0</span>/<span id="total-puntos-desktop">0</span></small>
        </div>
    </div>

    <div class="movil-panel d-md-none">
        <div class="row align-items-center">
            <div class="col-8">
                <h6 class="mb-1"><i class="fas fa-users me-1"></i><span id="nombre-grupo-movil">...</span></h6>
                <div class="progress" style="height: 5px;">
                    <div id="barra-progreso-movil" class="progress-bar bg-success" style="width: 0%"></div>
                </div>
                <small class="text-muted"><span id="puntos-completados-movil">0</span>/<span id="total-puntos-movil">0</span> puntos</small>
            </div>
            <div class="col-4 text-end">
                <button class="btn btn-primary btn-tactil rounded-circle" onclick="centrarMapa()">
                    <i class="fas fa-location-arrow"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalPrueba" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Resolver Prueba</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="descripcion-prueba"></p>
                    <input type="text" class="form-control" id="respuesta-prueba" placeholder="Escribe tu respuesta...">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="verificarRespuesta()">Enviar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/live.js') }}"></script>
    <script>
        function centrarMapa() {
            if (marcadorUsuario) {
                const latlng = marcadorUsuario.getLatLng();
                mapa.setView(latlng, 18);
                siguiendoUsuario = true;
            }
        }
    </script>
</body>
</html>