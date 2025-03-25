<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Gimcana</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="{{ asset('js/editargimcana.js') }}"></script>
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
                    <li><a href="{{ url('admin/index') }}">Inicio</a></li>
                    <li><a href="{{ url('admin/gimcana') }}">Gimcana</a></li>
                </ul>
            </nav>
        </header>

        <h1>Editar Gimcana</h1>

        <!-- Mostrar errores de validación -->
        @if($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulario para editar gimcana -->
        <form action="{{ route('admin.gimcana.update', $gimcana->id) }}" method="POST" class="form-custom">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $gimcana->nombre) }}">
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $gimcana->descripcion) }}</textarea>
            </div>

            <div class="form-group">
                <label>Lugares de Interés</label>
                <div class="etiquetas-grid">
                    @foreach($lugares as $lugar)
                        <div class="etiqueta-item">
                            <input type="checkbox" name="lugares[]" value="{{ $lugar->id }}" 
                                   id="lugar{{ $lugar->id }}" {{ $gimcana->lugares->contains($lugar->id) ? 'checked' : '' }}>
                            <label for="lugar{{ $lugar->id }}">{{ $lugar->nombre }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">Guardar</button>
                <a href="{{ route('admin.gimcana') }}" class="btn-submit-cancelar">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>

@section('styles')
<style>
    .admin-container {
        padding: 20px;
        max-width: 800px;
        margin: 0 auto;
    }

    h1 {
        color: #333;
        margin-bottom: 20px;
    }

    .form-custom {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        color: #333;
        font-weight: bold;
    }

    input[type="text"],
    textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    textarea {
        resize: vertical;
        min-height: 100px;
    }

    .etiquetas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 10px;
        margin-top: 10px;
    }

    .etiqueta-item {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-submit, .btn-cancelar {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        text-decoration: none;
        text-align: center;
    }

    .btn-submit {
        background-color: #4CAF50;
        color: white;
    }

    .btn-cancelar {
        background-color: #f44336;
        color: white;
    }

    .btn-submit:hover {
        background-color: #45a049;
    }

    .btn-cancelar:hover {
        background-color: #da190b;
    }

    .alert-error {
        background-color: #ffebee;
        border: 1px solid #ffcdd2;
        color: #c62828;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
    }

    .alert-error ul {
        margin: 0;
        padding-left: 20px;
    }
</style>
@endsection