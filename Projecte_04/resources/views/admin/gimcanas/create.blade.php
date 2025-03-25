<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Gimcana</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
</head>
<body>
    <div class="admin-container">
        <header>
            <div class="logo-container">
                <img src="{{ asset('img/logo.webp') }}" alt="Logo">
                <span class="user-name">{{ Auth::user()->nombre }}</span>
            </div>
            <nav>
                <ul>
                    <li><a href="{{ route('admin.index') }}"><i class="fas fa-home"></i> Inicio</a></li>
                    <li><a href="{{ route('admin.puntos') }}"><i class="fas fa-map-marker-alt"></i> Puntos de interés</a></li>
                    <li><a href="{{ route('admin.gimcanas') }}"><i class="fas fa-map-signs"></i> Gimcanas</a></li>
                </ul>
            </nav>
        </header>

        <h1>Crear Nueva Gimcana</h1>

        <form action="{{ route('admin.gimcanas.store') }}" method="POST" id="gimcanaForm">
            @csrf
            <div>
                <label for="nombre">Nombre de la Gimcana:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>

            <div>
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required></textarea>
            </div>

            <div>
                <label for="max_participantes">Número máximo de participantes:</label>
                <input type="number" id="max_participantes" name="max_participantes" min="1" value="10" required>
            </div>

            <div>
                <label>Selecciona los puntos de interés en orden:</label>
                <div class="lugares-grid">
                    @foreach($lugares as $lugar)
                        <div class="lugar-checkbox">
                            <input type="checkbox" 
                                   id="lugar_{{ $lugar->id }}" 
                                   name="lugares[]" 
                                   value="{{ $lugar->id }}"
                                   data-lat="{{ $lugar->latitud }}"
                                   data-lng="{{ $lugar->longitud }}"
                                   data-nombre="{{ $lugar->nombre }}">
                            <label for="lugar_{{ $lugar->id }}">{{ $lugar->nombre }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div id="acertijosContainer">
                <!-- Los acertijos se añadirán aquí dinámicamente -->
            </div>

            <div id="map" style="height: 400px; margin: 20px 0;"></div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Crear Gimcana
            </button>
        </form>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const map = L.map('map').setView([41.390205, 2.154007], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            const markers = [];
            const acertijosContainer = document.getElementById('acertijosContainer');
            const lugarCheckboxes = document.querySelectorAll('input[name="lugares[]"]');

            function actualizarAcertijos() {
                acertijosContainer.innerHTML = '';
                markers.forEach(marker => map.removeLayer(marker));
                markers.length = 0;

                const lugaresSeleccionados = Array.from(lugarCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => ({
                        id: cb.value,
                        nombre: cb.dataset.nombre,
                        lat: parseFloat(cb.dataset.lat),
                        lng: parseFloat(cb.dataset.lng)
                    }));

                lugaresSeleccionados.forEach((lugar, index) => {
                    // Crear formulario de acertijo
                    const acertijoHtml = `
                        <div class="acertijo-form">
                            <h3>Acertijo para ${lugar.nombre}</h3>
                            <input type="hidden" name="acertijos[${index}][lugar_id]" value="${lugar.id}">
                            
                            <div>
                                <label>Texto del acertijo:</label>
                                <textarea name="acertijos[${index}][texto]" required></textarea>
                            </div>
                            
                            <div>
                                <label>Pista:</label>
                                <textarea name="acertijos[${index}][pista]" required></textarea>
                            </div>
                            
                            <div>
                                <label>Ubicación del acertijo:</label>
                                <div class="coords-display">
                                    <input type="number" name="acertijos[${index}][latitud]" step="any" required readonly>
                                    <input type="number" name="acertijos[${index}][longitud]" step="any" required readonly>
                                </div>
                                <small>Haz clic en el mapa para establecer la ubicación del acertijo</small>
                            </div>
                        </div>
                    `;
                    acertijosContainer.insertAdjacentHTML('beforeend', acertijoHtml);

                    // Añadir marcador del lugar
                    const lugarMarker = L.marker([lugar.lat, lugar.lng], {
                        icon: L.divIcon({
                            className: 'custom-div-icon',
                            html: `<div style="background-color: #4CAF50; padding: 5px; border-radius: 50%; color: white;">${index + 1}</div>`,
                            iconSize: [30, 30],
                            iconAnchor: [15, 15]
                        })
                    }).addTo(map);
                    markers.push(lugarMarker);

                    // Permitir colocar marcador de acertijo
                    let acertijoMarker = null;
                    map.on('click', function(e) {
                        const inputs = acertijosContainer.querySelectorAll(`input[name="acertijos[${index}][latitud]"], input[name="acertijos[${index}][longitud]"]`);
                        if (acertijoMarker) {
                            map.removeLayer(acertijoMarker);
                        }
                        acertijoMarker = L.marker(e.latlng, {
                            icon: L.divIcon({
                                className: 'custom-div-icon',
                                html: `<div style="background-color: #f44336; padding: 5px; border-radius: 50%; color: white;">A${index + 1}</div>`,
                                iconSize: [30, 30],
                                iconAnchor: [15, 15]
                            })
                        }).addTo(map);
                        markers.push(acertijoMarker);
                        inputs[0].value = e.latlng.lat;
                        inputs[1].value = e.latlng.lng;
                    });
                });

                if (lugaresSeleccionados.length > 0) {
                    const bounds = L.latLngBounds(lugaresSeleccionados.map(l => [l.lat, l.lng]));
                    map.fitBounds(bounds);
                }
            }

            lugarCheckboxes.forEach(cb => {
                cb.addEventListener('change', actualizarAcertijos);
            });
        });
    </script>

    <style>
        .lugares-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }
        .lugar-checkbox {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .acertijo-form {
            background-color: #f8f9fa;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .acertijo-form h3 {
            margin-top: 0;
            color: #2c3e50;
        }
        .coords-display {
            display: flex;
            gap: 10px;
            margin-top: 5px;
        }
        .coords-display input {
            width: 150px;
        }
        .btn-submit {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-submit:hover {
            background-color: #218838;
        }
        small {
            color: #6c757d;
            display: block;
            margin-top: 5px;
        }
    </style>
</body>
</html>
