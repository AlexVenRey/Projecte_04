<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
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
    <div class="admin-container">
        <header>
            <div class="logo-container">
                <img src="{{ asset('img/logo.webp') }}" alt="Logo">
                <span class="user-name">{{ Auth::user()->nombre }}</span>
            </div>
            <nav>
                <ul>
                    <li><a href="{{ url('admin/puntos') }}">Puntos de interés</a></li>
                    <li><a href="{{ url('admin/gimcana') }}">Gimcana</a></li>
                </ul>
            </nav>
        </header>
        <h1>Bienvenido al Dashboard Admin</h1>
        <!-- Aquí va el contenido del panel de administración -->
        <div id="map" style="height: 500px; width: 100%;"></div>
    </div>
</body>
</html>