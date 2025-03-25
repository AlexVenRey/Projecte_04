<?php

namespace App\Http\Controllers;

use App\Models\User as Usuario;
use App\Models\Lugar;
use App\Models\Favorito;
use App\Models\Etiqueta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    public function index()
    {
        $etiquetas = Etiqueta::all();
        return view('cliente.index', compact('etiquetas'));
    }

    public function getLugares()
    {
        $lugares = Lugar::with('etiquetas')->get();
        return response()->json($lugares);
    }

    public function getEtiquetas()
    {
        $etiquetas = Etiqueta::all();
        return response()->json($etiquetas);
    }

    public function getFavoritos()
    {
        $usuario = Auth::user();
        $favoritos = Lugar::whereHas('usuarios', function ($query) use ($usuario) {
            $query->where('usuario_id', $usuario->id);
        })->with('etiquetas')->get();

        return response()->json($favoritos);
    }

    public function toggleFavorito(Lugar $lugar)
    {
        $usuario = Auth::user();
        $favorito = Favorito::where('usuario_id', $usuario->id)
            ->where('lugar_id', $lugar->id)
            ->first();

        if ($favorito) {
            $favorito->delete();
            $esFavorito = false;
        } else {
            Favorito::create([
                'usuario_id' => $usuario->id,
                'lugar_id' => $lugar->id
            ]);
            $esFavorito = true;
        }

        return response()->json(['esFavorito' => $esFavorito]);
    }

    public function getLugaresCercanos(Request $request)
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $distancia = $request->input('distancia', 1000); // metros

        // Convertir distancia a grados (aproximadamente)
        $grados = $distancia / 111000; // 1 grado ≈ 111 km

        $lugares = Lugar::with('etiquetas')
            ->whereBetween('latitud', [$lat - $grados, $lat + $grados])
            ->whereBetween('longitud', [$lng - $grados, $lng + $grados])
            ->get();

        // Filtrar por distancia exacta usando la fórmula de Haversine
        $lugaresFiltrados = $lugares->filter(function ($lugar) use ($lat, $lng, $distancia) {
            $distanciaReal = $this->calcularDistancia($lat, $lng, $lugar->latitud, $lugar->longitud);
            return $distanciaReal <= $distancia;
        });

        return response()->json($lugaresFiltrados->values());
    }

    private function calcularDistancia($lat1, $lon1, $lat2, $lon2)
    {
        $r = 6371000; // Radio de la Tierra en metros
        $p = pi() / 180;

        $a = 0.5 - cos(($lat2 - $lat1) * $p) / 2 +
            cos($lat1 * $p) * cos($lat2 * $p) *
            (1 - cos(($lon2 - $lon1) * $p)) / 2;

        return 2 * $r * asin(sqrt($a));
    }

    public function storePunto(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'etiquetas' => 'required|array',
            'color_marcador' => 'required|string',
        ]);

        try {
            // Crear el punto en la tabla lugares
            $lugar = Lugar::create([
                'nombre' => $request->nombre,
                'latitud' => $request->latitud,
                'longitud' => $request->longitud,
                'color_marcador' => $request->color_marcador,
            ]);

            // Asociar etiquetas al punto
            $lugar->etiquetas()->attach($request->etiquetas);

            // Asociar el punto al usuario autenticado en la tabla puntos_usuarios
            $usuario = Auth::user();
            if (!$usuario) {
                throw new \Exception('Usuario no autenticado.');
            }
            $usuario->puntos()->attach($lugar->id);

            return response()->json(['success' => true, 'lugar' => $lugar]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}