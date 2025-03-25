<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jugar Gimcana</title>
    <link rel="stylesheet" href="{{ asset('css/cliente.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
</head>
<body>
    <div class="cliente-container">
        <header>
            <div class="logo-container">
                <img src="{{ asset('img/logo.webp') }}" alt="Logo">
                <span class="user-name">{{ Auth::user()->nombre }}</span>
            </div>
            <nav>
                <ul>
                    <li><a href="{{ route('cliente.index') }}"><i class="fas fa-home"></i> Inicio</a></li>
                    <li><a href="{{ route('cliente.gimcanas.index') }}"><i class="fas fa-map-signs"></i> Gimcanas</a></li>
                    <li><a href="{{ route('cliente.gimcanas.mis-gimcanas') }}"><i class="fas fa-list"></i> Mis Gimcanas</a></li>
                </ul>
            </nav>
        </header>

        <div class="gimcana-container">
            <h1>{{ $gimcana->nombre }}</h1>
            <p class="descripcion">{{ $gimcana->descripcion }}</p>

            <div class="acertijo-container" id="acertijoContainer">
                @if($progreso->acertijo_actual)
                    <div class="acertijo-card">
                        <h2>Acertijo #{{ $progreso->acertijo_actual->orden }}</h2>
                        <p class="acertijo-texto">{{ $progreso->acertijo_actual->texto_acertijo }}</p>
                        
                        @if(!$progreso->acertijo_resuelto)
                            <form id="acertijoForm" class="acertijo-form">
                                <input type="text" name="respuesta" placeholder="Tu respuesta..." required>
                                <button type="submit">Verificar</button>
                            </form>
                        @else
                            <div class="pista-container">
                                <h3>¡Acertijo resuelto!</h3>
                                <p class="pista-texto">{{ $progreso->acertijo_actual->pista }}</p>
                                <p>Dirígete a la ubicación indicada en el mapa para encontrar el punto de interés.</p>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="completado-container">
                        <h2>¡Felicidades!</h2>
                        <p>Has completado la gimcana con éxito.</p>
                    </div>
                @endif
            </div>

            <div id="map" style="height: 400px; margin: 20px 0;"></div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const map = L.map('map').setView([41.390205, 2.154007], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            let userMarker = null;
            let watchId = null;
            let acertijoMarker = null;

            @if($progreso->acertijo_actual)
                // Mostrar ubicación del acertijo si está resuelto
                @if($progreso->acertijo_resuelto)
                    acertijoMarker = L.marker([
                        {{ $progreso->acertijo_actual->latitud_acertijo }}, 
                        {{ $progreso->acertijo_actual->longitud_acertijo }}
                    ], {
                        icon: L.divIcon({
                            className: 'custom-div-icon',
                            html: '<div style="background-color: #f44336; padding: 5px; border-radius: 50%; color: white;"><i class="fas fa-question"></i></div>',
                            iconSize: [30, 30],
                            iconAnchor: [15, 15]
                        })
                    }).addTo(map);

                    map.setView([
                        {{ $progreso->acertijo_actual->latitud_acertijo }},
                        {{ $progreso->acertijo_actual->longitud_acertijo }}
                    ], 15);
                @endif

                // Iniciar seguimiento de ubicación
                if ("geolocation" in navigator) {
                    watchId = navigator.geolocation.watchPosition(function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        if (userMarker) {
                            userMarker.setLatLng([lat, lng]);
                        } else {
                            userMarker = L.marker([lat, lng], {
                                icon: L.divIcon({
                                    className: 'custom-div-icon',
                                    html: '<div style="background-color: #2196F3; padding: 5px; border-radius: 50%; color: white;"><i class="fas fa-user"></i></div>',
                                    iconSize: [30, 30],
                                    iconAnchor: [15, 15]
                                })
                            }).addTo(map);
                        }

                        @if($progreso->acertijo_resuelto && !$progreso->lugar_encontrado)
                            // Verificar distancia al punto de interés
                            fetch('{{ route("cliente.gimcanas.verificar-ubicacion", $gimcana) }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    latitud: lat,
                                    longitud: lng
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    if (data.completado) {
                                        document.getElementById('acertijoContainer').innerHTML = `
                                            <div class="completado-container">
                                                <h2>¡Felicidades!</h2>
                                                <p>Has completado la gimcana con éxito.</p>
                                            </div>
                                        `;
                                    } else {
                                        document.getElementById('acertijoContainer').innerHTML = `
                                            <div class="acertijo-card">
                                                <h2>Acertijo #${data.siguienteAcertijo.orden}</h2>
                                                <p class="acertijo-texto">${data.siguienteAcertijo.texto}</p>
                                                <form id="acertijoForm" class="acertijo-form">
                                                    <input type="text" name="respuesta" placeholder="Tu respuesta..." required>
                                                    <button type="submit">Verificar</button>
                                                </form>
                                            </div>
                                        `;

                                        // Actualizar marcador del acertijo
                                        if (acertijoMarker) {
                                            map.removeLayer(acertijoMarker);
                                        }
                                        map.setView([data.siguienteAcertijo.latitud, data.siguienteAcertijo.longitud], 15);
                                    }
                                }
                            });
                        @endif
                    }, function(error) {
                        console.error('Error getting location:', error);
                        alert('No se pudo obtener tu ubicación. Por favor, activa el GPS y recarga la página.');
                    }, {
                        enableHighAccuracy: true,
                        maximumAge: 0
                    });
                } else {
                    alert('Tu navegador no soporta geolocalización.');
                }

                // Manejar envío de respuesta al acertijo
                const acertijoForm = document.getElementById('acertijoForm');
                if (acertijoForm) {
                    acertijoForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const respuesta = this.respuesta.value;

                        fetch('{{ route("cliente.gimcanas.verificar-acertijo", $gimcana) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ respuesta })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const acertijoContainer = document.getElementById('acertijoContainer');
                                acertijoContainer.innerHTML = `
                                    <div class="acertijo-card">
                                        <h3>¡Acertijo resuelto!</h3>
                                        <p class="pista-texto">${data.pista}</p>
                                        <p>Dirígete a la ubicación indicada en el mapa para encontrar el punto de interés.</p>
                                    </div>
                                `;

                                // Mostrar marcador del acertijo
                                if (acertijoMarker) {
                                    map.removeLayer(acertijoMarker);
                                }
                                acertijoMarker = L.marker([
                                    {{ $progreso->acertijo_actual->latitud_acertijo }},
                                    {{ $progreso->acertijo_actual->longitud_acertijo }}
                                ], {
                                    icon: L.divIcon({
                                        className: 'custom-div-icon',
                                        html: '<div style="background-color: #f44336; padding: 5px; border-radius: 50%; color: white;"><i class="fas fa-question"></i></div>',
                                        iconSize: [30, 30],
                                        iconAnchor: [15, 15]
                                    })
                                }).addTo(map);

                                map.setView([
                                    {{ $progreso->acertijo_actual->latitud_acertijo }},
                                    {{ $progreso->acertijo_actual->longitud_acertijo }}
                                ], 15);
                            } else {
                                alert('Respuesta incorrecta. Inténtalo de nuevo.');
                            }
                        });
                    });
                }
            @endif
        });
    </script>

    <style>
        .gimcana-container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .acertijo-container {
            margin: 20px 0;
        }
        .acertijo-card {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .acertijo-texto {
            font-size: 1.2em;
            margin: 15px 0;
        }
        .acertijo-form {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        .acertijo-form input {
            flex: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .acertijo-form button {
            padding: 8px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .acertijo-form button:hover {
            background-color: #45a049;
        }
        .pista-container {
            margin-top: 15px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 4px;
            border-left: 4px solid #17a2b8;
        }
        .pista-texto {
            font-style: italic;
            color: #2c3e50;
            margin: 10px 0;
        }
        .completado-container {
            text-align: center;
            padding: 40px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        .completado-container h2 {
            color: #28a745;
            margin-bottom: 15px;
        }
    </style>
</body>
</html>
