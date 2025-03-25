<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Gimcana</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

        <h1>Editar Gimcana</h1>

        <form action="{{ route('admin.gimcanas.update', $gimcana) }}" method="POST">
            @csrf
            @method('PUT')
            <div>
                <label for="nombre">Nombre de la Gimcana:</label>
                <input type="text" id="nombre" name="nombre" value="{{ $gimcana->nombre }}" required>
            </div>

            <div>
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required>{{ $gimcana->descripcion }}</textarea>
            </div>

            <div>
                <label for="max_participantes">Número máximo de participantes:</label>
                <input type="number" id="max_participantes" name="max_participantes" min="{{ $gimcana->participantes_actuales }}" value="{{ $gimcana->max_participantes }}" required>
                <small class="text-muted">Actualmente hay {{ $gimcana->participantes_actuales }} participantes</small>
            </div>

            <div>
                <label>Código de unión:</label>
                <div class="codigo-union">
                    <span class="badge">{{ $gimcana->codigo_union }}</span>
                </div>
            </div>

            <div>
                <label for="lugares">Selecciona los puntos de interés:</label>
                <div class="lugares-grid">
                    @foreach($lugares as $lugar)
                        <div class="lugar-checkbox">
                            <input type="checkbox" 
                                   id="lugar_{{ $lugar->id }}" 
                                   name="lugares[]" 
                                   value="{{ $lugar->id }}"
                                   {{ $gimcana->lugares->contains($lugar->id) ? 'checked' : '' }}>
                            <label for="lugar_{{ $lugar->id }}">{{ $lugar->nombre }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
                                    
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Guardar Cambios
            </button>
        </form>
    </div>

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
        .codigo-union {
            margin-top: 5px;
        }
        .codigo-union .badge {
            background-color: #17a2b8;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 16px;
        }
        .text-muted {
            color: #6c757d;
            font-size: 0.875em;
        }
    </style>
</body>
</html>
