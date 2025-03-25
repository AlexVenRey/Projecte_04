<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
</head>
<body onload="initMap()">
    <div class="admin-container">
        <header>
            <div class="logo-container">
                <img src="{{ asset('img/logo.webp') }}" alt="Logo">
                <span class="user-name">{{ Auth::user()->nombre }}</span>
            </div>
            <nav>
                <div class="hamburger-menu" onclick="toggleMenu()">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
                <ul class="nav-links">
                    <li><a href="{{ url('admin/puntos') }}">Puntos de interés</a></li>
                    <li><a href="{{ url('admin/gimcana') }}">Gimcana</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="logout-button">
                                <img src="{{ asset('img/cerrarsesion.png') }}" alt="Cerrar sesión" class="logout-icon">
                                Cerrar sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </header>
        <h1>Bienvenido a la página Admin</h1>
        <div id="map" style="height: 500px; width: 100%;"></div>
    </div>

    <script>
        function initMap() {
            var joan23 = [41.3479, 2.1045]; // Coordenadas del colegio Joan XXIII de Bellvitge
            var map = L.map('map').setView(joan23, 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var userMarker;

            // Función para calcular la distancia entre dos puntos en kilómetros
            function calculateDistance(lat1, lon1, lat2, lon2) {
                var R = 6371; // Radio de la Tierra en kilómetros
                var φ1 = lat1 * Math.PI / 180;
                var φ2 = lat2 * Math.PI / 180;
                var Δφ = (lat2 - lat1) * Math.PI / 180;
                var Δλ = (lon2 - lon1) * Math.PI / 180;

                var a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                        Math.cos(φ1) * Math.cos(φ2) *
                        Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
                var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

                return R * c; // Distancia en kilómetros
            }

            // Función para actualizar la ubicación del usuario
            function updateLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var userLat = position.coords.latitude;
                        var userLng = position.coords.longitude;

                        if (typeof userMarker !== 'undefined') {
                            map.removeLayer(userMarker);
                        }

                        userMarker = L.marker([userLat, userLng], {
                            icon: L.icon({
                                iconUrl: '{{ asset('img/ubicacion.png') }}',
                                iconSize: [40, 40],
                                iconAnchor: [20, 40],
                                popupAnchor: [0, -40]
                            })
                        }).addTo(map)
                        .bindPopup('Tu ubicación actual');

                        // Calcular distancias y tiempos para los puntos de interés
                        @foreach($lugares as $lugar)
                            var lugarLat = {{ $lugar->latitud }};
                            var lugarLng = {{ $lugar->longitud }};
                            var distancia = calculateDistance(userLat, userLng, lugarLat, lugarLng).toFixed(1); // Distancia en km
                            var tiempoCaminando = Math.round((distancia / 5) * 60); // Tiempo caminando (5 km/h)
                            var tiempoEnCoche = Math.round((distancia / 50) * 60); // Tiempo en coche (50 km/h)

                            // Crear un ícono dinámico basado en el color del marcador
                            var icon = L.divIcon({
                                className: 'custom-marker',
                                html: `
                                    <div style="
                                        position: relative;
                                        width: 15px;
                                        height: 15px;
                                        background-color: {{ $lugar->color_marcador }};
                                        border-radius: 50%;
                                        border: 2px solid white;
                                        box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
                                    ">
                                    </div>
                                    <div style="
                                        position: absolute;
                                        top: 15px;
                                        left: 4px;
                                        width: 0;
                                        height: 0;
                                        border-left: 5px solid transparent;
                                        border-right: 5px solid transparent;
                                        border-top: 7px solid {{ $lugar->color_marcador }};
                                    ">
                                    </div>
                                `,
                                iconSize: [15, 22],
                                iconAnchor: [7.5, 22],
                                popupAnchor: [0, -22]
                            });

                            // Crear el marcador con el diseño dinámico
                            var marker = L.marker([lugarLat, lugarLng], {
                                icon: icon
                            }).addTo(map)
                            .bindPopup(`
                                <strong>{{ $lugar->nombre }}</strong><br>
                                {{ $lugar->descripcion }}<br>
                                <strong>Distancia:</strong> ${distancia} km<br>
                                <strong>Tiempo estimado:</strong><br>
                                <img src="{{ asset('img/caminando.webp') }}" alt="Caminando" style="width: 20px;"> ${tiempoCaminando} min<br>
                            `);
                        @endforeach
                    });
                } else {
                    alert("Geolocalización no es soportada por este navegador.");
                }
            }

            // Actualizar la ubicación cada 5 segundos
            setInterval(updateLocation, 5000);

            // Llamar a la función por primera vez
            updateLocation();
        }

        function toggleMenu() {
            const navLinks = document.querySelector('.nav-links');
            navLinks.classList.toggle('active');
        }
    </script>
</body>
</html>