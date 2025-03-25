<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gimcanas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Lista de Gimcanas</h1>
        <ul class="list-group">
            @foreach($gimcanas as $gimcana)
                <li class="list-group-item">
                    <h5>{{ $gimcana->nombre }}</h5>
                    <p>{{ $gimcana->descripcion }}</p>
                    <strong>Usuarios del grupo:</strong>
                    <ul>
                        @foreach($gimcana->usuarios as $usuario)
                            <li>{{ $usuario->nombre }}</li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
