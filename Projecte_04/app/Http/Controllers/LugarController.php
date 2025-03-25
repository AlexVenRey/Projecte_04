<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lugar;
use App\Models\Etiqueta;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

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

        $lugar = Lugar::create([
            'nombre' => $request->nombre,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'descripcion' => $request->descripcion,
            'color_marcador' => $request->color_marcador,
            'creado_por' => Auth::id(),
        ]);

        $lugar->etiquetas()->attach($request->etiquetas);

        return redirect()->route('admin.puntos')->with('success');
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

        $punto = Lugar::findOrFail($id);

        $punto->nombre = $request->nombre;
        $punto->latitud = $request->latitud;
        $punto->longitud = $request->longitud;
        $punto->descripcion = $request->descripcion;
        $punto->color_marcador = $request->color_marcador;

        $punto->save();
        $punto->etiquetas()->sync($request->etiquetas);

        return redirect()->route('admin.puntos')->with('success');
    }

    public function destroy($id)
    {
        $punto = Lugar::findOrFail($id);

        // Eliminar el icono si existe
        if ($punto->icono && File::exists(public_path('img/' . $punto->icono))) {
            File::delete(public_path('img/' . $punto->icono));
        }

        $punto->etiquetas()->detach();
        $punto->delete();

        return redirect()->route('admin.puntos')->with('success');
    }
}