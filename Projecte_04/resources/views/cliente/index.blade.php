<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliente Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/cliente.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
</head>
<body onload="initMap()">
    <div class="cliente-container">
        <header>
            <div class="logo-container">
                <img src="{{ asset('img/logo.webp') }}" alt="Logo">
                <span class="user-name">{{ Auth::user()->nombre }}</span>
            </div>
            <nav>
                <ul>
                    <li><a href="{{ url('cliente/puntos') }}">Puntos de interés</a></li>
                    <li><a href="{{ url('cliente/gimcana') }}">Gimcana</a></li>
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
        <h1>Bienvenido al Dashboard Cliente</h1>
        <div id="map" style="height: 500px; width: 100%;"></div>
    </div>

    <script>
        function initMap() {
            // Coordenadas iniciales del mapa (Joan XXIII Bellvitge)
            var map = L.map('map').setView([41.3479, 2.1045], 15);

            // Añadir la capa de OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Añadir marcadores para cada lugar
            @foreach($lugares as $lugar)
                var marker = L.marker([{{ $lugar->latitud }}, {{ $lugar->longitud }}])
                    .addTo(map)
                    .bindPopup(`
                        <strong>{{ $lugar->nombre }}</strong><br>
                        {{ $lugar->descripcion }}<br>
                        <img src="{{ asset('img/' . $lugar->icono) }}" alt="Icono" style="width: 50px; height: 50px;">
                    `);
            @endforeach

            // Añadir marcador de ubicación del usuario
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var userLat = position.coords.latitude;
                    var userLng = position.coords.longitude;

                    var userIcon = L.icon({
                        iconUrl: '{{ asset('img/ubicacion.png') }}',
                        iconSize: [40, 40],
                        iconAnchor: [20, 40],
                        popupAnchor: [0, -40]
                    });

                    L.marker([userLat, userLng], {icon: userIcon})
                        .addTo(map)
                        .bindPopup('Tu ubicación actual');
                });
            }
        }
    </script>
</body>
</html>