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
            'icono' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'etiquetas' => 'required|array',
        ]);

        // Mover el archivo a la carpeta public/img
        $icono = $request->file('icono');
        $iconoName = time() . '_' . $icono->getClientOriginalName();
        $icono->move(public_path('img'), $iconoName);

        $lugar = Lugar::create([
            'nombre' => $request->nombre,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'descripcion' => $request->descripcion,
            'icono' => $iconoName,
            'creado_por' => Auth::id(),
        ]);

        $lugar->etiquetas()->attach($request->etiquetas);

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
            'icono' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'etiquetas' => 'required|array',
        ]);

        $punto = Lugar::findOrFail($id);

        $punto->nombre = $request->nombre;
        $punto->latitud = $request->latitud;
        $punto->longitud = $request->longitud;
        $punto->descripcion = $request->descripcion;

        if ($request->hasFile('icono')) {
            $icono = $request->file('icono');
            $iconoName = time() . '_' . $icono->getClientOriginalName();
            $icono->move(public_path('img'), $iconoName);
            $punto->icono = $iconoName;
        }

        $punto->save();
        $punto->etiquetas()->sync($request->etiquetas);

        return redirect()->route('admin.puntos')->with('success', 'Punto de interés actualizado correctamente.');
    }

    public function destroy($id)
    {
        $punto = Lugar::findOrFail($id);

        // Eliminar el icono del servidor si existe
        if ($punto->icono && File::exists(public_path('img/' . $punto->icono))) {
            File::delete(public_path('img/' . $punto->icono));
        }

        // Eliminar las relaciones con etiquetas
        $punto->etiquetas()->detach();

        // Eliminar el punto de interés
        $punto->delete();

        return redirect()->route('admin.puntos')->with('success', 'Punto de interés eliminado correctamente.');
    }
}