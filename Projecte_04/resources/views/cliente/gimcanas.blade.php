<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de GimCanas</title>
    <link rel="stylesheet" href="{{ asset('css/cliente.css') }}">
</head>
<body>
    <div class="cliente-container">
        <h1>Lista de Gimcanas</h1>
        <ul>
            @foreach($gimkanas as $gimkana)
                <li>
                    <strong>{{ $gimkana->nombre }}</strong>
                    <ul>
                        @foreach($gimkana->puntosControl as $punto)
                            <li>
                                Punto: {{ $punto->lugar->nombre }} - Pista: {{ $punto->pista }}
                                <ul>
                                    @foreach($punto->pruebas as $prueba)
                                        <li>Prueba: {{ $prueba->descripcion }}</li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>

        <h2>Rutas</h2>
        <ul>
            @foreach($rutas as $ruta)
                <li>
                    Desde: {{ $ruta->origen }} - Hasta: {{ $ruta->destino }} - Tiempo estimado: {{ $ruta->tiempo_estimado }} minutos
                </li>
            @endforeach
        </ul>
    </div>
</body>
</html>
