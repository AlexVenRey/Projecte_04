<?php

namespace App\Http\Controllers;

use App\Models\Lugar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMakerController extends Controller
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
            'color_marcador' => 'required|string'
        ]);

        $lugar = Lugar::create([
            'nombre' => $request->nombre,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'descripcion' => $request->descripcion,
            'color_marcador' => $request->color_marcador,
            'creado_por' => Auth::id()
        ]);

        return redirect()->route('cliente.index')
            ->with('success', 'Marcador creado correctamente');
    }

    public function destroy(Lugar $lugar)
    {
        // Verificar que el marcador pertenece al usuario
        if ($lugar->creado_por !== Auth::id()) {
            return response()->json([
                'success' => false,
                'mensaje' => 'No tienes permiso para eliminar este marcador'
            ], 403);
        }

        $lugar->delete();

        return response()->json([
            'success' => true,
            'mensaje' => 'Marcador eliminado correctamente'
        ]);
    }
}
