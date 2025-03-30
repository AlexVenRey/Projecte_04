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

    public function siguientePunto($gimcana_id)
    {
        try {
            // Obtener el usuario y la gimcana
            $usuario = auth()->user();
            $gimcana = Gimcana::findOrFail($gimcana_id);
            
            // Verificar si el usuario pertenece a un grupo en esta gimcana
            $grupo = $usuario->grupos()
                ->whereHas('gimcanas', function($q) use ($gimcana_id) {
                    $q->where('gimcanas.id', $gimcana_id);
                })->first();

            if (!$grupo) {
                return response()->json([
                    'success' => false,
                    'message' => 'No perteneces a ningún grupo en esta gimcana'
                ], 403);
            }

            // Obtener los lugares de la gimcana
            $lugaresGimcana = $gimcana->lugares()->pluck('lugares.id');
            
            // Obtener puntos de control ya completados
            $puntosCompletados = DB::table('progreso_gimcana')
                ->where('usuario_id', $usuario->id)
                ->pluck('punto_control_id');

            // Obtener el siguiente punto de control
            $siguientePunto = PuntoControl::whereIn('lugar_id', $lugaresGimcana)
                ->whereNotIn('id', $puntosCompletados)
                ->with(['lugar', 'prueba'])
                ->first();

            if (!$siguientePunto) {
                return response()->json([
                    'success' => true,
                    'data' => null,
                    'message' => 'Has completado todos los puntos de control'
                ]);
            }

            // Asegurarse de que el punto de control tiene una prueba asociada
            if (!$siguientePunto->prueba) {
                // Crear una prueba por defecto si no existe
                $prueba = Prueba::create([
                    'punto_control_id' => $siguientePunto->id,
                    'descripcion' => "¿Cuál es el código que ves en {$siguientePunto->lugar->nombre}?",
                    'respuesta' => '123'
                ]);
                $siguientePunto->load('prueba');
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $siguientePunto->id,
                    'lugar' => [
                        'id' => $siguientePunto->lugar->id,
                        'nombre' => $siguientePunto->lugar->nombre,
                        'latitud' => $siguientePunto->lugar->latitud,
                        'longitud' => $siguientePunto->lugar->longitud
                    ],
                    'pista' => $siguientePunto->pista ?? "Encuentra {$siguientePunto->lugar->nombre}",
                    'prueba' => [
                        'descripcion' => $siguientePunto->prueba->descripcion
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en siguientePunto: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el siguiente punto: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verificarPrueba(Request $request)
    {
        try {
            // Validar los datos de entrada
            $request->validate([
                'punto_control_id' => 'required|exists:puntos_control,id',
                'respuesta' => 'required|string',
                'gimcana_id' => 'required|exists:gimcanas,id'
            ]);

            // Obtener el punto de control con su prueba
            $puntoControl = PuntoControl::with(['prueba', 'lugar'])->findOrFail($request->punto_control_id);
            
            // Log para depuración
            \Log::info('Verificando prueba:', [
                'punto_control_id' => $request->punto_control_id,
                'respuesta_usuario' => $request->respuesta,
                'respuesta_correcta' => $puntoControl->prueba->respuesta ?? 'No hay respuesta definida'
            ]);
            
            if (!$puntoControl->prueba) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay prueba asociada a este punto de control'
                ], 400);
            }

            // Obtener el grupo actual del usuario
            $usuario = auth()->user();
            $gimcana = Gimcana::findOrFail($request->gimcana_id);
            $grupoActual = $usuario->grupos()
                ->whereHas('gimcanas', function($q) use ($gimcana) {
                    $q->where('gimcanas.id', $gimcana->id);
                })->first();

            if (!$grupoActual) {
                return response()->json([
                    'success' => false,
                    'message' => 'No perteneces a ningún grupo en esta gimcana'
                ], 403);
            }

            // Verificar si ya completó este punto
            $yaCompletado = DB::table('progreso_gimcana')
                ->where('usuario_id', $usuario->id)
                ->where('punto_control_id', $puntoControl->id)
                ->exists();

            if ($yaCompletado) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya has completado este punto de control'
                ]);
            }

            // Normalizar y comparar respuestas
            $respuestaUsuario = strtolower(trim($request->respuesta));
            $respuestasValidas = array_map('trim', explode(',', strtolower($puntoControl->prueba->respuesta)));
            
            // Log de comparación
            \Log::info('Comparando respuestas:', [
                'respuesta_usuario' => $respuestaUsuario,
                'respuestas_validas' => $respuestasValidas
            ]);

            $esCorrecta = in_array($respuestaUsuario, $respuestasValidas);

            if (!$esCorrecta) {
                return response()->json([
                    'success' => false,
                    'message' => 'Respuesta incorrecta. Inténtalo de nuevo.'
                ]);
            }

            // Registrar el progreso
            DB::beginTransaction();
            try {
                DB::table('progreso_gimcana')->insert([
                    'usuario_id' => $usuario->id,
                    'punto_control_id' => $puntoControl->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Verificar si el grupo ha completado la gimcana
                $puntosControlTotal = $gimcana->lugares()->count();
                $puntosCompletadosUsuario = DB::table('progreso_gimcana')
                    ->where('usuario_id', $usuario->id)
                    ->count();

                $gimcanaCompletada = $puntosCompletadosUsuario >= $puntosControlTotal;

                DB::commit();

                // Log de éxito
                \Log::info('Prueba completada con éxito:', [
                    'usuario_id' => $usuario->id,
                    'punto_control_id' => $puntoControl->id,
                    'gimcana_completada' => $gimcanaCompletada
                ]);

                return response()->json([
                    'success' => true,
                    'message' => '¡Respuesta correcta! Has superado esta prueba.',
                    'gimcana_completada' => $gimcanaCompletada,
                    'grupo_ganador' => $gimcanaCompletada ? [
                        'nombre' => $grupoActual->nombre,
                        'usuarios' => $grupoActual->usuarios,
                        'tiempo_total' => 'Completado'
                    ] : null
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Error al registrar progreso: ' . $e->getMessage());
                throw $e;
            }

        } catch (\Exception $e) {
            \Log::error('Error en verificarPrueba: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar la prueba: ' . $e->getMessage()
            ], 500);
        }
    }

    public function actualizarPosicion(Request $request)
    {
        try {
            $usuario = auth()->user();
            
            // Validar los datos recibidos
            $request->validate([
                'latitud' => 'required|numeric',
                'longitud' => 'required|numeric',
                'gimcana_id' => 'required|exists:gimcanas,id'
            ]);

            // Actualizar la ubicación del usuario como JSON
            $usuario->update([
                'ubicacion_actual' => json_encode([
                    'latitud' => $request->latitud,
                    'longitud' => $request->longitud
                ])
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error actualizando posición: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al actualizar la posición'
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
