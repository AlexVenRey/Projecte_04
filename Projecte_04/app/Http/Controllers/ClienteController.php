<?php

namespace App\Http\Controllers;

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
        $favoritos = Lugar::whereHas('favoritos', function($query) use ($usuario) {
            $query->where('usuario_id', $usuario->id);
        })->with(['etiquetas', 'creador'])->get();
        
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

        return response()->json($lugares->values());
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
}