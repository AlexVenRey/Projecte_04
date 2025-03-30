<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lugar; // Importamos el modelo Lugar
use App\Models\Gimcana; // Importamos el modelo Gimcana
use Illuminate\Support\Facades\Auth; // Importamos la clase Auth para acceder al usuario autenticado
use Illuminate\Support\Facades\DB; // Importamos la clase DB para transacciones

class GimcanaController extends Controller
{
    // Método para mostrar la lista de gimcanas
    public function index()
    {
        // Obtener todas las gimcanas de la base de datos con los lugares asociados
        $gimcanas = Gimcana::with('lugares')->get(); // Obtener todas las gimcanas con los lugares asociados
        return view('admin.gimcana', compact('gimcanas')); // Pasamos las gimcanas a la vista
    }

    // Método para mostrar el formulario de crear una nueva gimcana
    public function create()
    {
        // Obtener todos los lugares de la base de datos
        $lugares = Lugar::all(); // Traemos todos los puntos de interés
        return view('admin.creargimcana', compact('lugares')); // Pasamos los lugares a la vista
    }

    // Método para guardar la nueva gimcana en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'lugares' => 'required|array',
        ]);

        DB::transaction(function () use ($request) {
            $gimcana = Gimcana::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'creado_por' => Auth::id(),
            ]);

            $gimcana->lugares()->attach($request->lugares);
        });

        return redirect()->route('admin.gimcana')->with('success', 'Gimcana creada correctamente.');
    }

    // Método para eliminar una gimcana
    public function destroy($gimcanaId)
    {
        DB::transaction(function () use ($gimcanaId) {
            $gimcana = Gimcana::findOrFail($gimcanaId);
            $gimcana->lugares()->detach();
            $gimcana->delete();
        });

        return redirect()->route('admin.gimcana')->with('success', 'Gimcana eliminada correctamente.');
    }

    // Método para editar una gimcana
    public function edit($id)
    {
        $gimcana = Gimcana::findOrFail($id);
        $lugares = Lugar::all(); // Carga todos los lugares de interés disponibles

        return view('admin.editargimcana', compact('gimcana', 'lugares'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'lugares' => 'array',
        ]);

        DB::transaction(function () use ($request, $id) {
            $gimcana = Gimcana::findOrFail($id);

            $gimcana->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
            ]);

            $gimcana->lugares()->sync($request->lugares);
        });

        return redirect()->route('admin.gimcana')->with('success', 'Gimcana actualizada correctamente.');
    }

}