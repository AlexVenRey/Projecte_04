<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prueba;
use App\Models\Lugar;
use Illuminate\Http\Request;

class PruebaController extends Controller
{
    public function index()
    {
        $pruebas = Prueba::with(['lugar', 'grupos'])->get();
        return view('admin.pruebas.index', compact('pruebas'));
    }

    public function create()
    {
        $lugares = Lugar::all();
        return view('admin.pruebas.create', compact('lugares'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'titulo' => 'required|max:255',
            'descripcion' => 'required',
            'pista' => 'required',
            'lugar_id' => 'required|exists:lugares,id'
        ]);

        Prueba::create($validatedData);

        return redirect()->route('admin.pruebas.index')
            ->with('success', 'Prueba creada correctamente');
    }

    public function edit(Prueba $prueba)
    {
        $lugares = Lugar::all();
        return view('admin.pruebas.edit', compact('prueba', 'lugares'));
    }

    public function update(Request $request, Prueba $prueba)
    {
        $validatedData = $request->validate([
            'titulo' => 'required|max:255',
            'descripcion' => 'required',
            'pista' => 'required',
            'lugar_id' => 'required|exists:lugares,id'
        ]);

        $prueba->update($validatedData);

        return redirect()->route('admin.pruebas.index')
            ->with('success', 'Prueba actualizada correctamente');
    }

    public function destroy(Prueba $prueba)
    {
        // Verificar si hay grupos que han completado esta prueba
        if ($prueba->grupos()->wherePivot('completada', true)->count() > 0) {
            return redirect()->route('admin.pruebas.index')
                ->with('error', 'No se puede eliminar la prueba porque hay grupos que ya la han completado');
        }

        $prueba->grupos()->detach(); // Eliminar relaciones con grupos
        $prueba->delete();

        return redirect()->route('admin.pruebas.index')
            ->with('success', 'Prueba eliminada correctamente');
    }

    // API Endpoints para la gimcana
    public function getPruebasByGrupo($grupoId)
    {
        $pruebas = Prueba::with(['lugar', 'grupos' => function($query) use ($grupoId) {
            $query->where('grupos.id', $grupoId);
        }])->get()->map(function($prueba) {
            return [
                'id' => $prueba->id,
                'titulo' => $prueba->titulo,
                'lugar' => [
                    'nombre' => $prueba->lugar->nombre,
                    'latitud' => $prueba->lugar->latitud,
                    'longitud' => $prueba->lugar->longitud
                ],
                'completada' => $prueba->grupos->first() ? $prueba->grupos->first()->pivot->completada : false
            ];
        });

        return response()->json($pruebas);
    }

    public function verificarCompletitud($pruebaId, $grupoId)
    {
        $prueba = Prueba::findOrFail($pruebaId);
        $completada = $prueba->grupos()
            ->where('grupos.id', $grupoId)
            ->first()
            ->pivot
            ->completada ?? false;

        return response()->json(['completada' => $completada]);
    }
}
