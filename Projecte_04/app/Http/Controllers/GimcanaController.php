<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lugar; // Importamos el modelo Lugar
use App\Models\Gimcana; // Importamos el modelo Gimcana
use App\Models\PuntoControl;
use App\Models\Prueba;
use App\Models\Grupo;
use Illuminate\Support\Facades\Auth; // Importamos la clase Auth para acceder al usuario autenticado
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GimcanaController extends Controller
{
    // Método para mostrar la lista de gimcanas
    public function index()
    {
        // Obtener todas las gimcanas de la base de datos con los lugares asociados
        $gimcanas = Gimcana::with('lugares')->get(); // Obtener todas las gimcanas con los lugares asociados
        return view('admin.gimcana', compact('gimcanas')); // Pasamos las gimcanas a la vista
    }

    // Método para mostrar el formulario de crear una nueva gimcana
    public function create()
    {
        // Obtener todos los lugares de la base de datos
        $lugares = Lugar::all(); // Traemos todos los puntos de interés
        return view('admin.creargimcana', compact('lugares')); // Pasamos los lugares a la vista
    }

    // Método para guardar la nueva gimcana en la base de datos
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'lugares' => 'required|array', // Validar que se envíen múltiples lugares
        ]);
    
        // Crear una nueva gimcana
        $gimcana = new Gimcana();
        $gimcana->nombre = $request->nombre;
        $gimcana->descripcion = $request->descripcion;
        $gimcana->creado_por = Auth::id(); // Guardamos el ID del usuario autenticado
        $gimcana->save(); // Guardamos la gimcana
    
        // Asociar los puntos de interés seleccionados a la gimcana
        $gimcana->lugares()->attach($request->lugares);
    
        // Redirigir con un mensaje de éxito
        return redirect()->route('admin.gimcana')->with('success', 'Gimcana creada correctamente.');
    }

    // Método para eliminar una gimcana
    public function destroy($gimcanaId)
    {
        // Buscar la gimcana en la base de datos
        $gimcana = Gimcana::findOrFail($gimcanaId);

        // Eliminar la gimcana
        $gimcana->delete();

        // Redirigir con mensaje de éxito
        return redirect()->route('admin.gimcana')->with('success', 'Gimcana eliminada correctamente.');
    }

    // Método para editar una gimcana
    public function edit($id)
    {
        $gimcana = Gimcana::findOrFail($id);
        $lugares = Lugar::all(); // Carga todos los lugares de interés disponibles

        return view('admin.editargimcana', compact('gimcana', 'lugares'));
    }

    public function update(Request $request, $id)
    {
        // Validación de los datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'lugares' => 'array', // Asegura que se recibe un array de lugares
        ]);

        // Buscar la gimcana en la base de datos
        $gimcana = Gimcana::findOrFail($id);

        // Actualizar los datos de la gimcana
        $gimcana->nombre = $request->nombre;
        $gimcana->descripcion = $request->descripcion;

        // Guardar cambios en la base de datos
        $gimcana->save();

        // Actualizar la relación con los lugares
        $gimcana->lugares()->sync($request->lugares);

        // Redirigir con un mensaje de éxito
        return redirect()->route('admin.gimcana')->with('success', 'Gimcana actualizada correctamente.');
    }

    /**
     * Verifica la respuesta de una prueba
     */
    public function verificarPrueba(Request $request)
    {
        try {
            $puntoControl = PuntoControl::with(['prueba', 'lugar'])->findOrFail($request->punto_control_id);
            $gimcana = Gimcana::findOrFail($request->gimcana_id);
            $grupoActual = auth()->user()->grupos()->whereHas('gimcanas', function($q) use ($gimcana) {
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

    /**
     * Obtiene el siguiente punto de control
     */
    public function siguientePunto(Gimcana $gimcana)
    {
        try {
            $usuario = auth()->user();
            $grupo = $usuario->grupos()->whereHas('gimcanas', function($q) use ($gimcana) {
                $q->where('gimcanas.id', $gimcana->id);
            })->first();

            if (!$grupo) {
                return response()->json(['error' => 'No perteneces a ningún grupo en esta gimcana'], 403);
            }

            $puntosCompletados = DB::table('progreso_gimcana')
                ->where('usuario_id', $usuario->id)
                ->pluck('punto_control_id');

            $siguientePunto = PuntoControl::whereHas('lugar', function($query) use ($gimcana) {
                $query->whereHas('gimcanas', function($q) use ($gimcana) {
                    $q->where('gimcanas.id', $gimcana->id);
                });
            })
            ->whereNotIn('id', $puntosCompletados)
            ->with(['lugar', 'prueba'])
            ->first();

            if (!$siguientePunto) {
                return response()->json(null);
            }

            return response()->json([
                'id' => $siguientePunto->id,
                'lugar' => [
                    'id' => $siguientePunto->lugar->id,
                    'nombre' => $siguientePunto->lugar->nombre,
                    'latitud' => $siguientePunto->lugar->latitud,
                    'longitud' => $siguientePunto->lugar->longitud
                ],
                'pista' => $siguientePunto->pista,
                'prueba' => [
                    'descripcion' => $siguientePunto->prueba->descripcion,
                    'respuesta' => $siguientePunto->prueba->respuesta
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener el siguiente punto: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Obtiene el progreso actual de la gimcana
     */
    public function progreso(Gimcana $gimcana)
    {
        try {
            $usuario = auth()->user();
            $totalPuntos = $gimcana->lugares()->count();
            $puntosCompletados = DB::table('progreso_gimcana')
                ->where('usuario_id', $usuario->id)
                ->count();

            return response()->json([
                'success' => true,
                'total' => $totalPuntos,
                'completados' => $puntosCompletados
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener el progreso'
            ], 500);
        }
    }
}