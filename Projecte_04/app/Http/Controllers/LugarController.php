<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lugar;
use App\Models\Etiqueta;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LugarController extends Controller
{
    public function index()
    {
        $lugares = Lugar::with('etiquetas')->get();
        return view('admin.puntos', compact('lugares'));
    }

    public function showMap()
    {
        $lugares = Lugar::with('etiquetas')->get();
        return view('admin.index', compact('lugares'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:lugares,nombre',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'descripcion' => 'required|string',
            'color_marcador' => 'required|string',
            'etiquetas' => 'required|array',
        ]);

        DB::transaction(function () use ($request) {
            $lugar = Lugar::create([
                'nombre' => $request->nombre,
                'latitud' => $request->latitud,
                'longitud' => $request->longitud,
                'descripcion' => $request->descripcion,
                'color_marcador' => $request->color_marcador,
                'creado_por' => Auth::id(),
            ]);

            $lugar->etiquetas()->attach($request->etiquetas);
        });

        return redirect()->route('admin.puntos')->with('success', 'Punto de interés añadido correctamente.');
    }

    public function edit($id)
    {
        $punto = Lugar::with('etiquetas')->findOrFail($id);
        $etiquetas = Etiqueta::all();
        return view('admin.editarpunto', compact('punto', 'etiquetas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:lugares,nombre,' . $id,
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'descripcion' => 'required|string',
            'color_marcador' => 'required|string',
            'etiquetas' => 'required|array',
        ]);

        DB::transaction(function () use ($request, $id) {
            $punto = Lugar::findOrFail($id);

            $punto->update([
                'nombre' => $request->nombre,
                'latitud' => $request->latitud,
                'longitud' => $request->longitud,
                'descripcion' => $request->descripcion,
                'color_marcador' => $request->color_marcador,
            ]);

            $punto->etiquetas()->sync($request->etiquetas);
        });

        return redirect()->route('admin.puntos')->with('success', 'Punto de interés actualizado correctamente.');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $punto = Lugar::findOrFail($id);

            // Eliminar el icono si existe
            if ($punto->icono && File::exists(public_path('img/' . $punto->icono))) {
                File::delete(public_path('img/' . $punto->icono));
            }

            $punto->etiquetas()->detach();
            $punto->delete();
        });

        return redirect()->route('admin.puntos')->with('success', 'Punto de interés eliminado correctamente.');
    }

    public function misFavoritos()
    {
        try {
            $usuario = Auth::user();
            $lugares = $usuario->favoritos()
                ->with('etiquetas')
                ->get()
                ->map(function ($lugar) {
                    $lugar->es_favorito = true;
                    return $lugar;
                });

            return response()->json($lugares);
        } catch (\Exception $e) {
            Log::error('Error al obtener favoritos: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener favoritos'], 500);
        }
    }

    public function toggleFavorito(Request $request)
    {
        try {
            $lugar_id = $request->lugar_id;
            $usuario = Auth::user();
            
            $esFavorito = $usuario->favoritos()->toggle($lugar_id);
            
            return response()->json([
                'success' => true,
                'esFavorito' => count($esFavorito['attached']) > 0
            ]);
        } catch (\Exception $e) {
            Log::error('Error al toggle favorito: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar favorito'
            ], 500);
        }
    }
}