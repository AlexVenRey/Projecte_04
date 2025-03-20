<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lugar; // Importamos el modelo Lugar
use App\Models\Gimcana; // Importamos el modelo Gimcana

class GimcanaController extends Controller
{
    public function index()
    {
        return view('admin.gimcana');
    }

    public function create()
    {
        // Obtener todos los lugares de la base de datos
        $lugares = Lugar::all();
        return view('admin.creargimcana', compact('lugares'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'lugares' => 'required|array', // Validar que se envíen múltiples lugares
        ]);
    
        // Crear nueva Gimcana
        $gimcana = new Gimcana();
        $gimcana->nombre = $request->nombre;
        $gimcana->descripcion = $request->descripcion;
        $gimcana->save();
    
        // Asociar los puntos de interés a la Gimcana
        $gimcana->lugares()->attach($request->lugares);
    
        return redirect()->route('admin.gimcana')->with('success', 'Gimcana creada correctamente.');
    }}
