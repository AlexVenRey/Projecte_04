<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lugar;
use App\Models\Etiqueta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'direccion' => 'required',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'descripcion' => 'required',
            'color' => 'nullable|max:7',
            'icono' => 'nullable|image|max:2048',
            'etiquetas' => 'array'
        ]);

        if ($request->hasFile('icono')) {
            $path = $request->file('icono')->store('iconos', 'public');
            $validatedData['icono'] = $path;
        }

        $lugar = Lugar::create($validatedData);

        if (isset($validatedData['etiquetas'])) {
            $lugar->etiquetas()->sync($validatedData['etiquetas']);
        }

        return redirect()->route('admin.lugares.index')
            ->with('success', 'Lugar creado correctamente');
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
            'direccion' => 'required',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'descripcion' => 'required',
            'color' => 'nullable|max:7',
            'icono' => 'nullable|image|max:2048',
            'etiquetas' => 'array'
        ]);

        if ($request->hasFile('icono')) {
            if ($lugar->icono) {
                Storage::disk('public')->delete($lugar->icono);
            }
            $path = $request->file('icono')->store('iconos', 'public');
            $validatedData['icono'] = $path;
        }

        $lugar->update($validatedData);

        if (isset($validatedData['etiquetas'])) {
            $lugar->etiquetas()->sync($validatedData['etiquetas']);
        }

        return redirect()->route('admin.lugares.index')
            ->with('success', 'Lugar actualizado correctamente');
    }

    public function destroy(Lugar $lugar)
    {
        if ($lugar->icono) {
            Storage::disk('public')->delete($lugar->icono);
        }
        
        $lugar->delete();

        return redirect()->route('admin.lugares.index')
            ->with('success', 'Lugar eliminado correctamente');
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
