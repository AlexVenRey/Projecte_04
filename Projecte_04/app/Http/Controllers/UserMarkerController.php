<?php

namespace App\Http\Controllers;

use App\Models\Lugar;
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

        $lugar = Lugar::create([
            'nombre' => $request->nombre,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'descripcion' => $request->descripcion,
            'color_marcador' => $request->color_marcador ?? '#3388ff',
            'creado_por' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'lugar' => $lugar,
            'es_propietario' => true
        ], 201);
    }

    public function destroy(Lugar $lugar)
    {
        if (Auth::user()->rol !== 'admin' && $lugar->creado_por !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar este marcador');
        }

        $lugar->delete();
        return response()->json(['success' => true], 204);
    }
}