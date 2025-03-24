<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lugar;
use App\Models\Etiqueta;
use Illuminate\Http\Request;

class LugarController extends Controller
{
    public function index()
    {
        $lugares = Lugar::with('etiquetas')->get();
        return view('admin.lugares.index', compact('lugares'));
    }

    public function create()
    {
        $etiquetas = Etiqueta::all();
        return view('admin.lugares.create', compact('etiquetas'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|max:255',
            'descripcion' => 'required',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'etiquetas' => 'required|array|min:1',
            'etiquetas.*' => 'exists:etiquetas,id',
            'color_marcador' => 'required|string|max:7'
        ]);

        $lugar = Lugar::create([
            'nombre' => $validatedData['nombre'],
            'descripcion' => $validatedData['descripcion'],
            'latitud' => $validatedData['latitud'],
            'longitud' => $validatedData['longitud'],
            'color_marcador' => $validatedData['color_marcador'],
            'creado_por' => auth()->id()
        ]);

        $lugar->etiquetas()->attach($validatedData['etiquetas']);

        return redirect()->route('admin.lugares.index')
            ->with('success', 'Lugar creado correctamente.');
    }

    public function edit(Lugar $lugar)
    {
        $etiquetas = Etiqueta::all();
        return view('admin.lugares.edit', compact('lugar', 'etiquetas'));
    }

    public function update(Request $request, Lugar $lugar)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|max:255',
            'descripcion' => 'required',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'etiquetas' => 'required|array|min:1',
            'etiquetas.*' => 'exists:etiquetas,id',
            'color_marcador' => 'required|string|max:7'
        ]);

        $lugar->update([
            'nombre' => $validatedData['nombre'],
            'descripcion' => $validatedData['descripcion'],
            'latitud' => $validatedData['latitud'],
            'longitud' => $validatedData['longitud'],
            'color_marcador' => $validatedData['color_marcador']
        ]);

        $lugar->etiquetas()->sync($validatedData['etiquetas']);

        return redirect()->route('admin.lugares.index')
            ->with('success', 'Lugar actualizado correctamente.');
    }

    public function destroy(Lugar $lugar)
    {
        $lugar->delete();
        return redirect()->route('admin.lugares.index')
            ->with('success', 'Lugar eliminado correctamente.');
    }

    // API endpoints para AJAX
    public function getLugares()
    {
        $lugares = Lugar::with('etiquetas')->get();
        return response()->json($lugares);
    }

    public function getLugarByEtiqueta($etiquetaId)
    {
        $lugares = Lugar::whereHas('etiquetas', function($query) use ($etiquetaId) {
            $query->where('etiquetas.id', $etiquetaId);
        })->with('etiquetas')->get();
        
        return response()->json($lugares);
    }

    public function getLugaresCercanos(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'distance' => 'required|numeric' // distancia en metros
        ]);

        $lat = $request->lat;
        $lng = $request->lng;
        $distance = $request->distance;

        // Fórmula Haversine para calcular distancia
        $lugares = Lugar::selectRaw("*, 
            (6371000 * acos(cos(radians(?)) * cos(radians(latitud)) * 
            cos(radians(longitud) - radians(?)) + 
            sin(radians(?)) * sin(radians(latitud)))) AS distance", 
            [$lat, $lng, $lat])
            ->having("distance", "<=", $distance)
            ->orderBy("distance")
            ->with('etiquetas')
            ->get();

        return response()->json($lugares);
    }
}
