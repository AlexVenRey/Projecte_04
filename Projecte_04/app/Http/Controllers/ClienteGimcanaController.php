<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gimcana; // Importamos el modelo Gimcana
use App\Models\Lugar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClienteGimcanaController extends Controller
{
    // Método para mostrar la lista de gimcanas para el cliente
    public function index()
    {
        // Obtener todas las gimcanas con los usuarios asociados
        $gimcanas = Gimcana::with('usuarios')->get(); // Asegúrate de que 'usuarios' es la relación correcta
        return view('cliente.gimcana', compact('gimcanas')); // Pasamos las gimcanas a la vista del cliente
    }

    public function live($gimcana_id)
    {
        // Obtener la gimcana específica
        $gimcana = Gimcana::with('grupos.usuarios')->findOrFail($gimcana_id);

        // Pasar la gimcana a la vista
        return view('cliente.live', compact('gimcana'));
    }

    public function getLugares($gimcana_id)
    {
        try {
            $gimcana = Gimcana::findOrFail($gimcana_id);
            
            $lugares = $gimcana->lugares()
                ->select('lugares.id', 'lugares.nombre', 'lugares.descripcion', 'lugares.latitud', 'lugares.longitud')
                ->get();

            return response()->json([
                'success' => true,
                'lugares' => $lugares
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener lugares: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los lugares de la gimcana'
            ], 500);
        }
    }
}
