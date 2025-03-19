<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etiqueta;
use Illuminate\Http\Request;

class EtiquetaController extends Controller
{
    public function index()
    {
        $etiquetas = Etiqueta::withCount('lugares')->get();
        return view('admin.etiquetas.index', compact('etiquetas'));
    }

    public function create()
    {
        return view('admin.etiquetas.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|max:255|unique:etiquetas',
            'descripcion' => 'nullable'
        ]);

        Etiqueta::create($validatedData);

        return redirect()->route('admin.etiquetas.index')
            ->with('success', 'Etiqueta creada correctamente');
    }

    public function edit(Etiqueta $etiqueta)
    {
        return view('admin.etiquetas.edit', compact('etiqueta'));
    }

    public function update(Request $request, Etiqueta $etiqueta)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|max:255|unique:etiquetas,nombre,' . $etiqueta->id,
            'descripcion' => 'nullable'
        ]);

        $etiqueta->update($validatedData);

        return redirect()->route('admin.etiquetas.index')
            ->with('success', 'Etiqueta actualizada correctamente');
    }

    public function destroy(Etiqueta $etiqueta)
    {
        if ($etiqueta->lugares()->count() > 0) {
            return redirect()->route('admin.etiquetas.index')
                ->with('error', 'No se puede eliminar la etiqueta porque tiene lugares asociados');
        }

        $etiqueta->delete();

        return redirect()->route('admin.etiquetas.index')
            ->with('success', 'Etiqueta eliminada correctamente');
    }
}
