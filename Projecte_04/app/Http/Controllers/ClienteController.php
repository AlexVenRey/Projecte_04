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
        try {
            $user = Auth::user();
            
            $lugares = Lugar::with(['etiquetas', 'creador'])
                ->when($user->rol === 'usuario', function($query) use ($user) {
                    return $query->where('creado_por', $user->id)
                        ->orWhereHas('creador', function($q) {
                            $q->where('rol', 'admin');
                        });
                })
                ->get()
                ->map(function($lugar) use ($user) {
                    $lugar->es_propietario = $lugar->creado_por === $user->id;
                    return $lugar;
                });

            return response()->json([
                'success' => true,
                'data' => $lugares
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al cargar lugares',
                'message' => $e->getMessage()
            ], 500);
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
<<<<<<< HEAD
        })->with(['etiquetas', 'creador'])->get();
        
=======
        })->with('etiquetas')->get();

>>>>>>> 8939b8ce9a954f21618fc7e95c3e7bb10c5754af
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

    public function buscarCercanos(Request $request)
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $distancia = $request->input('distancia', 1000);
        $user = Auth::user();

        $grados = $distancia / 111000;

        $lugares = Lugar::with(['etiquetas', 'creador'])
            ->when($user->rol === 'usuario', function($query) use ($user) {
                return $query->where('creado_por', $user->id)
                    ->orWhereHas('creador', function($q) {
                        $q->where('rol', 'admin');
                    });
            })
            ->whereBetween('latitud', [$lat - $grados, $lat + $grados])
            ->whereBetween('longitud', [$lng - $grados, $lng + $grados])
            ->get()
            ->filter(function($lugar) use ($lat, $lng, $distancia) {
                $distanciaReal = $this->calcularDistancia($lat, $lng, $lugar->latitud, $lugar->longitud);
                return $distanciaReal <= $distancia;
            })
            ->map(function($lugar) use ($user) {
                $lugar->es_propietario = $lugar->creado_por === $user->id;
                return $lugar;
            });

<<<<<<< HEAD
        return response()->json($lugares->values());
=======
        // Filtrar por distancia exacta usando la fórmula de Haversine
        $lugaresFiltrados = $lugares->filter(function ($lugar) use ($lat, $lng, $distancia) {
            $distanciaReal = $this->calcularDistancia($lat, $lng, $lugar->latitud, $lugar->longitud);
            return $distanciaReal <= $distancia;
        });

        return response()->json($lugaresFiltrados->values());
>>>>>>> 8939b8ce9a954f21618fc7e95c3e7bb10c5754af
    }

    private function calcularDistancia($lat1, $lon1, $lat2, $lon2)
    {
        $r = 6371000;
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