<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gimkana;
use App\Models\Ruta;
use Illuminate\Support\Facades\Auth;

class GimcanaController extends Controller
{
    public function index()
    {
        $usuarioId = Auth::id();

        // Obtener las gimkanas y sus detalles
        $gimkanas = Gimkana::with(['puntosControl.pruebas', 'puntosControl.grupos'])
            ->get();

        // Obtener las rutas del usuario
        $rutas = Ruta::where('usuario_id', $usuarioId)->get();

        return view('cliente.gimcanas', compact('gimkanas', 'rutas'));
    }
}
