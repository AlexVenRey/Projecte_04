<?php
namespace App\Http\Controllers;

use App\Models\Lugar;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        // Obtener todos los lugares sin cargar relaciones
        $lugares = Lugar::all(); // Solo obtiene los datos de la tabla `lugares`
        return view('cliente.index', compact('lugares')); // Pasar la variable $lugares a la vista
    }
}