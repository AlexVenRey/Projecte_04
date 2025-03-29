<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gimcana; // Importamos el modelo Gimcana
use App\Models\Lugar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\PuntoControl;
use App\Models\Prueba;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    public function obtenerGrupoActual(Request $request, $gimcana_id)
    {
        try {
            $usuario = auth()->user();
            $grupo = $usuario->grupos()
                ->whereHas('gimcanas', function($q) use ($gimcana_id) {
                    $q->where('gimcanas.id', $gimcana_id);
                })
                ->with(['usuarios' => function($q) {
                    $q->select('usuarios.id', 'usuarios.nombre')
                        ->withPivot('esta_listo');
                }])
                ->first();

            return response()->json([
                'success' => true,
                'grupo' => $grupo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el grupo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function siguientePunto(Request $request, $gimcana_id)
    {
        try {
            $usuario = auth()->user();
            $gimcana = Gimcana::findOrFail($gimcana_id);

            // Verificar si el usuario pertenece a un grupo en esta gimcana
            $grupo = $usuario->grupos()
                ->whereHas('gimcanas', function($q) use ($gimcana_id) {
                    $q->where('gimcanas.id', $gimcana_id);
                })->first();

            if (!$grupo) {
                return response()->json([
                    'error' => 'No perteneces a ningún grupo en esta gimcana'
                ], 403);
            }

            // Obtener puntos ya completados
            $puntosCompletados = DB::table('progreso_gimcana')
                ->where('usuario_id', $usuario->id)
                ->pluck('punto_control_id');

            // Obtener todos los puntos de control de la gimcana
            $puntosControl = PuntoControl::whereHas('lugar', function($query) use ($gimcana) {
                $query->whereHas('gimcanas', function($q) use ($gimcana) {
                    $q->where('gimcanas.id', $gimcana->id);
                });
            })
            ->whereNotIn('id', $puntosCompletados)
            ->with(['lugar', 'prueba'])
            ->first();

            if (!$puntosControl) {
                return response()->json([
                    'success' => true,
                    'message' => 'Has completado todos los puntos de control',
                    'data' => null
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $puntosControl->id,
                    'lugar' => [
                        'id' => $puntosControl->lugar->id,
                        'nombre' => $puntosControl->lugar->nombre,
                        'latitud' => $puntosControl->lugar->latitud,
                        'longitud' => $puntosControl->lugar->longitud
                    ],
                    'pista' => $puntosControl->pista,
                    'prueba' => [
                        'descripcion' => $puntosControl->prueba->descripcion,
                        'respuesta' => $puntosControl->prueba->respuesta
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el siguiente punto: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verificarPrueba(Request $request)
    {
        try {
            $puntoControl = PuntoControl::with(['prueba', 'lugar'])->findOrFail($request->punto_control_id);
            $gimcana = Gimcana::findOrFail($request->gimcana_id);
            $grupoActual = auth()->user()->grupos()
                ->whereHas('gimcanas', function($q) use ($gimcana) {
                    $q->where('gimcanas.id', $gimcana->id);
                })->first();

            if (!$grupoActual) {
                return response()->json([
                    'success' => false,
                    'message' => 'No perteneces a ningún grupo en esta gimcana'
                ], 403);
            }

            // Verificar la respuesta
            $prueba = $puntoControl->prueba;
            $respuestaCorrecta = strtolower(trim($prueba->respuesta)) === strtolower(trim($request->respuesta));

            if (!$respuestaCorrecta) {
                return response()->json([
                    'success' => false,
                    'message' => 'Respuesta incorrecta'
                ]);
            }

            // Registrar progreso
            DB::table('progreso_gimcana')->insert([
                'usuario_id' => auth()->id(),
                'punto_control_id' => $puntoControl->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Verificar si el grupo ha completado la gimcana
            $puntosControlTotal = $gimcana->lugares()->count();
            $usuariosGrupo = $grupoActual->usuarios()->count();
            
            $puntosCompletadosGrupo = DB::table('progreso_gimcana')
                ->join('usuarios_grupos', 'progreso_gimcana.usuario_id', '=', 'usuarios_grupos.usuario_id')
                ->where('usuarios_grupos.grupo_id', $grupoActual->id)
                ->count();

            $gimcanaCompletada = false;
            $grupoGanador = null;
            $tiempoTotal = null;

            if ($puntosCompletadosGrupo >= $puntosControlTotal * $usuariosGrupo) {
                $gimcanaCompletada = true;

                if ($gimcana->estado !== 'completada') {
                    $grupoGanador = $grupoActual;
                    
                    // Calcular tiempo total
                    $primerPunto = DB::table('progreso_gimcana')
                        ->join('usuarios_grupos', 'progreso_gimcana.usuario_id', '=', 'usuarios_grupos.usuario_id')
                        ->where('usuarios_grupos.grupo_id', $grupoActual->id)
                        ->min('created_at');
                    
                    $ultimoPunto = DB::table('progreso_gimcana')
                        ->join('usuarios_grupos', 'progreso_gimcana.usuario_id', '=', 'usuarios_grupos.usuario_id')
                        ->where('usuarios_grupos.grupo_id', $grupoActual->id)
                        ->max('created_at');

                    $tiempoTotal = Carbon::parse($primerPunto)->diffForHumans(Carbon::parse($ultimoPunto));
                    
                    $gimcana->update(['estado' => 'completada']);
                }
            }

            return response()->json([
                'success' => true,
                'gimcana_completada' => $gimcanaCompletada,
                'grupo_ganador' => $grupoGanador ? [
                    'nombre' => $grupoGanador->nombre,
                    'usuarios' => $grupoGanador->usuarios,
                    'tiempo_total' => $tiempoTotal
                ] : null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar la prueba: ' . $e->getMessage()
            ], 500);
        }
    }

    public function actualizarPosicion(Request $request)
    {
        try {
            $request->validate([
                'latitud' => 'required|numeric',
                'longitud' => 'required|numeric',
                'gimcana_id' => 'required|exists:gimcanas,id'
            ]);

            $usuario = auth()->user();
            $usuario->update([
                'ubicacion_actual' => DB::raw("POINT({$request->longitud}, {$request->latitud})")
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Posición actualizada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la posición: ' . $e->getMessage()
            ], 500);
        }
    }

    public function progreso(Request $request, $gimcana_id)
    {
        try {
            $usuario = auth()->user();
            $gimcana = Gimcana::findOrFail($gimcana_id);
            
            $totalPuntos = $gimcana->lugares()->count();
            $puntosCompletados = DB::table('progreso_gimcana')
                ->join('puntos_control', 'progreso_gimcana.punto_control_id', '=', 'puntos_control.id')
                ->join('lugares', 'puntos_control.lugar_id', '=', 'lugares.id')
                ->join('gimcana_lugar', 'lugares.id', '=', 'gimcana_lugar.lugar_id')
                ->where('gimcana_lugar.gimcana_id', $gimcana_id)
                ->where('progreso_gimcana.usuario_id', $usuario->id)
                ->count();

            return response()->json([
                'success' => true,
                'total' => $totalPuntos,
                'completados' => $puntosCompletados
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el progreso: ' . $e->getMessage()
            ], 500);
        }
    }
}
