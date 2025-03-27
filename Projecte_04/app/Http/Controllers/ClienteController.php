<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lugar;
use App\Models\Favorito;
use App\Models\Etiqueta;
use App\Models\Gimcana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClienteController extends Controller
{
    public function index()
    {
        $etiquetas = Etiqueta::all();
        return view('cliente.index', compact('etiquetas'));
    }

    public function getLugares()
    {
        try {
            $lugares = Lugar::with('etiquetas')->get();
            // Añadir el atributo es_favorito a cada lugar
            $lugares->each(function ($lugar) {
                $lugar->es_favorito = $lugar->esFavorito;
            });
            return response()->json($lugares);
        } catch (\Exception $e) {
            Log::error('Error obteniendo lugares: ' . $e->getMessage());
            return response()->json(['error' => 'Error obteniendo lugares'], 500);
        }
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

    public function misFavoritos()
    {
        try {
            $usuario = Auth::user();
            
            if (!$usuario) {
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }

            $favoritos = $usuario->favoritos()
                ->with('etiquetas')
                ->get();

            return response()->json($favoritos);
        } catch (\Exception $e) {
            Log::error('Error en misFavoritos: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function toggleFavorito(Request $request)
    {
        try {
            $lugar_id = $request->lugar_id;
            $usuario = Auth::user();
            
            // Verificar si ya es favorito
            $esFavorito = $usuario->favoritos()->where('lugar_id', $lugar_id)->exists();
            
            if ($esFavorito) {
                $usuario->favoritos()->detach($lugar_id);
                $esFavorito = false;
            } else {
                $usuario->favoritos()->attach($lugar_id);
                $esFavorito = true;
            }
            
            return response()->json([
                'success' => true,
                'esFavorito' => $esFavorito,
                'message' => $esFavorito ? 'Añadido a favoritos' : 'Eliminado de favoritos'
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggle favorito: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar favorito'
            ], 500);
        }
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
            $usuario = Auth::user();
            
            // Crear el punto en la tabla lugares
            $lugar = Lugar::create([
                'nombre' => $request->nombre,
                'latitud' => $request->latitud,
                'longitud' => $request->longitud,
                'color_marcador' => $request->color_marcador,
                'creado_por' => $usuario->id
            ]);

            // Asociar etiquetas al punto
            $lugar->etiquetas()->attach($request->etiquetas);

            // Asociar el punto al usuario
            $usuario->puntos()->attach($lugar->id);

            return response()->json(['success' => true, 'lugar' => $lugar]);
        } catch (\Exception $e) {
            Log::error('Error creando punto: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function gimcanas()
    {
        $gimcanas = Gimcana::with(['grupos.usuarios'])->get();
        return view('cliente.gimcana', compact('gimcanas'));
    }

    public function live($gimcana_id)
    {
        $gimcana = Gimcana::with(['puntos'])->findOrFail($gimcana_id);
        return view('cliente.live', compact('gimcana'));
    }
}