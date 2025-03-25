<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lugar; // Importamos el modelo Lugar
use App\Models\Gimcana; // Importamos el modelo Gimcana
use Illuminate\Support\Facades\Auth; // Importamos la clase Auth para acceder al usuario autenticado

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
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'lugares' => 'required|array', // Validar que se envíen múltiples lugares
        ]);
    
        // Crear una nueva gimcana
        $gimcana = new Gimcana();
        $gimcana->nombre = $request->nombre;
        $gimcana->descripcion = $request->descripcion;
        $gimcana->creado_por = Auth::id(); // Guardamos el ID del usuario autenticado
        $gimcana->save(); // Guardamos la gimcana
    
        // Asociar los puntos de interés seleccionados a la gimcana
        $gimcana->lugares()->attach($request->lugares);
    
        // Redirigir con un mensaje de éxito
        return redirect()->route('admin.gimcana')->with('success', 'Gimcana creada correctamente.');
    }

    // Método para eliminar una gimcana
    public function destroy($gimcanaId)
    {
        // Buscar la gimcana en la base de datos
        $gimcana = Gimcana::findOrFail($gimcanaId);

        // Eliminar la gimcana
        $gimcana->delete();

        // Redirigir con mensaje de éxito
        return redirect()->route('admin.gimcana')->with('success', 'Gimcana eliminada correctamente.');
    }
}
