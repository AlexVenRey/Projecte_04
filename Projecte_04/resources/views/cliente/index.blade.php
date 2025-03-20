<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliente Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/cliente.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
    <script>
        function initMap() {
            var joan23 = [41.3479, 2.1045]; // Coordenadas del colegio Joan XXIII de Bellvitge
            var map = L.map('map').setView(joan23, 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            @foreach($lugares as $lugar)
                var marker = L.marker([{{ $lugar->latitud }}, {{ $lugar->longitud }}]).addTo(map)
                    .bindPopup('{{ $lugar->nombre }}<br>{{ $lugar->descripcion }}');
            @endforeach
        }
    </script>
</head>
<body onload="initMap()">
    <div class="cliente-container">
        <header>
            <button onclick="window.location.href='{{ route('cliente.gimkanas') }}'">Ver Gimkanas</button>
        </header>
        <h1>Bienvenido Cliente</h1>
        <!-- Aquí va el contenido del panel de cliente -->
        <div id="map" style="height: 500px; width: 100%;"></div>
    </div>
</body>
</html>
