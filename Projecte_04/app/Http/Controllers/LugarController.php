<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lugar;

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
}