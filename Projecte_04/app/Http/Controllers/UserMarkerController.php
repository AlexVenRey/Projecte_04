<?php

namespace App\Http\Controllers;

use App\Models\Lugar;
use App\Models\Favorito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMarkerController extends Controller
{
    public function create()
    {
        return view('cliente.crear-marcador');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:lugares,nombre',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'descripcion' => 'required|string',
            'color_marcador' => 'sometimes|string'
        ]);

        // Crear el lugar
        $lugar = Lugar::create([
            'nombre' => $request->nombre,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'descripcion' => $request->descripcion,
            'color_marcador' => $request->color_marcador ?? '#3388ff',
            'creado_por' => Auth::id()
        ]);

        // Añadir automáticamente a favoritos
        Favorito::create([
            'usuario_id' => Auth::id(),
            'lugar_id' => $lugar->id
        ]);

        return response()->json([
            'success' => true,
            'lugar' => $lugar,
            'es_propietario' => true,
            'es_favorito' => true // Indicar que ya es favorito
        ], 201);
    }

    public function destroy(Lugar $lugar)
    {
        if (Auth::user()->rol !== 'admin' && $lugar->creado_por !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar este marcador');
        }

        // Eliminar también de favoritos si existe
        Favorito::where('lugar_id', $lugar->id)
               ->where('usuario_id', Auth::id())
               ->delete();

        $lugar->delete();
        return response()->json(['success' => true], 204);
    }
}