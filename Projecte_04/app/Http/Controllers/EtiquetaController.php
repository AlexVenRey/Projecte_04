<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etiqueta;

class EtiquetaController extends Controller
{
    public function index()
    {
        $etiquetas = Etiqueta::all();
        return view('admin.etiquetas.index', compact('etiquetas'));
    }

    public function create()
    {
        return view('admin.etiquetas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:etiquetas,nombre'
        ]);

        Etiqueta::create([
            'nombre' => $request->nombre
        ]);

        return redirect()->route('admin.etiquetas')->with('success', 'Etiqueta creada correctamente');
    }

    public function edit(Etiqueta $etiqueta)
    {
        return view('admin.etiquetas.edit', compact('etiqueta'));
    }

    public function update(Request $request, Etiqueta $etiqueta)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:etiquetas,nombre,' . $etiqueta->id
        ]);

        $etiqueta->update([
            'nombre' => $request->nombre
        ]);

        return redirect()->route('admin.etiquetas')->with('success', 'Etiqueta actualizada correctamente');
    }

    public function destroy(Etiqueta $etiqueta)
    {
        $etiqueta->delete();
        return redirect()->route('admin.etiquetas')->with('success', 'Etiqueta eliminada correctamente');
    }
}
