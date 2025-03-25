<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lugar;
use App\Models\Etiqueta;
use App\Models\Prueba;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalLugares = Lugar::count();
        $totalEtiquetas = Etiqueta::count();
        $totalPruebas = Prueba::count();

        return view('admin.dashboard', compact('totalLugares', 'totalEtiquetas', 'totalPruebas'));
    }
}
