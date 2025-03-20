<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gimcana;
use App\Models\Ruta;
use Illuminate\Support\Facades\Auth;
use App\Models\Lugar;

class GimcanaController extends Controller
{
    public function index()
    {
        $gimcanas = Gimcana::all(); // Asegúrate de importar el modelo Gimcana
        return view('cliente.gimcanas', compact('gimcanas'));
    }
}
