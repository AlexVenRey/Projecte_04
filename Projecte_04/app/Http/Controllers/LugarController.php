<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lugar;
use App\Models\Etiqueta;
use Illuminate\Support\Facades\File;

class LugarController extends Controller
{
    public function index()
    {
        $lugares = Lugar::with('etiquetas')->get();
        return view('admin.puntos', compact('lugares'));
    }

    public function showMap()
    {
        $lugares = Lugar::all();
        return view('admin.index', compact('lugares'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
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
        ]);

        $lugar->etiquetas()->attach($request->etiquetas);

        return redirect()->route('admin.puntos')->with('success', 'Punto de interés añadido correctamente.');
    }
}