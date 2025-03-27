<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Gimcana;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ClienteGrupoController extends Controller
{
    public function obtenerGrupos($gimcana_id)
    {
        try {
            $gimcana = Gimcana::findOrFail($gimcana_id);
            
            // Obtener los grupos con sus usuarios y el estado de "esta_listo"
            $grupos = $gimcana->grupos()
                ->with(['usuarios' => function($query) {
                    $query->select('usuarios.*', 'usuarios_grupos.esta_listo')
                        ->withPivot('esta_listo');
                }])
                ->get();

            return response()->json([
                'success' => true,
                'grupos' => $grupos
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al obtener grupos: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los grupos'
            ], 500);
        }
    }

    public function crearGrupo(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:100',
                'gimcana_id' => 'required|exists:gimcanas,id'
            ]);

            // Verificar si el usuario ya está en un grupo para esta gimcana
            $usuarioTieneGrupo = Auth::user()
                ->grupos()
                ->whereHas('gimcanas', function($query) use ($request) {
                    $query->where('gimcanas.id', $request->gimcana_id);
                })
                ->exists();

            if ($usuarioTieneGrupo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya perteneces a un grupo en esta gimcana'
                ], 422);
            }

            // Verificar si ya existe un grupo con el mismo nombre en la gimcana
            $grupoExistente = Grupo::where('nombre', $request->nombre)
                ->whereHas('gimcanas', function($query) use ($request) {
                    $query->where('gimcanas.id', $request->gimcana_id);
                })
                ->exists();

            if ($grupoExistente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya existe un grupo con este nombre en la gimcana'
                ], 422);
            }

            // Crear el grupo
            $grupo = Grupo::create([
                'nombre' => $request->nombre,
                'descripcion' => 'Grupo para la gimcana ' . $request->gimcana_id
            ]);

            // Asociar el grupo con la gimcana
            $grupo->gimcanas()->attach($request->gimcana_id);

            // Añadir el usuario al grupo
            $grupo->usuarios()->attach(Auth::id(), [
                'esta_listo' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Grupo creado correctamente',
                'grupo' => $grupo
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al crear el grupo: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el grupo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function unirseGrupo(Request $request)
    {
        // Validaciones detalladas
        $validator = Validator::make($request->all(), [
            'grupo_id' => 'required|exists:grupos,id',
            'gimcana_id' => 'required|exists:gimcanas,id'
        ], [
            'grupo_id.required' => 'ID de grupo no proporcionado',
            'grupo_id.exists' => 'El grupo seleccionado no existe',
            'gimcana_id.required' => 'ID de gimcana no proporcionado',
            'gimcana_id.exists' => 'La gimcana seleccionada no existe'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $usuario = User::find(Auth::id());

            // Verificar si el grupo pertenece a la gimcana
            $grupoEnGimcana = Grupo::whereHas('gimcanas', function($query) use ($request) {
                $query->where('gimcanas.id', $request->gimcana_id);
            })->where('id', $request->grupo_id)->exists();

            if (!$grupoEnGimcana) {
                return response()->json([
                    'success' => false,
                    'message' => 'El grupo no pertenece a esta gimcana'
                ], 422);
            }

            // Verificar si el usuario ya está en algún grupo de esta gimcana
            $gruposUsuario = $usuario->grupos()
                ->whereHas('gimcanas', function($q) use ($request) {
                    $q->where('gimcanas.id', $request->gimcana_id);
                })->exists();

            if ($gruposUsuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya estás en un grupo de esta gimcana'
                ], 422);
            }

            // Verificar el número máximo de usuarios por grupo
            $grupo = Grupo::find($request->grupo_id);
            if ($grupo->usuarios()->count() >= 5) {
                return response()->json([
                    'success' => false,
                    'message' => 'El grupo ya ha alcanzado el número máximo de participantes'
                ], 422);
            }

            // Añadir al usuario al grupo
            $usuario->grupos()->attach($request->grupo_id);

            return response()->json([
                'success' => true,
                'message' => 'Te has unido al grupo exitosamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al unirse al grupo: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al unirse al grupo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function marcarListo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'grupo_id' => 'required|exists:grupos,id',
                'gimcana_id' => 'required|exists:gimcanas,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verificar que el usuario pertenece al grupo
            $grupo = Grupo::findOrFail($request->grupo_id);
            $usuarioEnGrupo = $grupo->usuarios()
                ->where('usuario_id', Auth::id())
                ->first();

            if (!$usuarioEnGrupo) {
                return response()->json([
                    'success' => false,
                    'message' => 'No perteneces a este grupo'
                ], 403);
            }

            // Actualizar el estado del usuario en el grupo
            $actualizado = $grupo->usuarios()->updateExistingPivot(Auth::id(), [
                'esta_listo' => true,
                'updated_at' => now()
            ]);

            if (!$actualizado) {
                throw new \Exception('No se pudo actualizar el estado');
            }

            // Verificar si la actualización fue exitosa
            $estadoActualizado = $grupo->usuarios()
                ->where('usuario_id', Auth::id())
                ->first()
                ->pivot
                ->esta_listo;

            if (!$estadoActualizado) {
                throw new \Exception('La actualización no se reflejó en la base de datos');
            }

            return response()->json([
                'success' => true,
                'message' => '¡Has marcado que estás listo!',
                'estado' => $estadoActualizado
            ]);

        } catch (\Exception $e) {
            Log::error('Error al marcar como listo: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al marcar como listo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verificarTodosListos($gimcana_id)
    {
        try {
            $gimcana = Gimcana::with(['grupos.usuarios' => function($query) {
                $query->select('usuarios.id', 'usuarios.nombre')
                      ->withPivot('esta_listo');
            }])->findOrFail($gimcana_id);

            // Log para ver los grupos y usuarios
            Log::info('Grupos y usuarios:', [
                'grupos' => $gimcana->grupos->map(function($grupo) {
                    return [
                        'grupo_id' => $grupo->id,
                        'usuarios' => $grupo->usuarios->map(function($usuario) {
                            return [
                                'usuario_id' => $usuario->id,
                                'esta_listo' => $usuario->pivot->esta_listo
                            ];
                        })
                    ];
                })
            ]);

            $todosListos = $gimcana->grupos->every(function($grupo) {
                return $grupo->usuarios->every(function($usuario) {
                    return $usuario->pivot->esta_listo == true;
                });
            });

            Log::info('Resultado de verificación:', ['todos_listos' => $todosListos]);

            return response()->json([
                'success' => true,
                'todos_listos' => $todosListos,
                'message' => $todosListos ? 'Todos los usuarios están listos' : 'Aún hay usuarios que no están listos'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al verificar usuarios listos: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar el estado de los usuarios'
            ], 500);
        }
    }

    public function iniciarGimcana($gimcana_id)
    {
        try {
            $gimcana = Gimcana::findOrFail($gimcana_id);
            $grupo = $gimcana->grupos()->whereHas('usuarios', function($query) {
                $query->where('usuario_id', Auth::id());
            })->first();

            if (!$grupo) {
                return response()->json([
                    'success' => false,
                    'message' => 'No estás en ningún grupo de esta gimcana'
                ]);
            }

            // Verificar que todos los usuarios estén listos
            $todosListos = $grupo->usuarios()->where('esta_listo', false)->count() === 0;
            
            if (!$todosListos) {
                return response()->json([
                    'success' => false,
                    'message' => 'No todos los usuarios están listos'
                ]);
            }

            // Actualizar el estado de la gimcana
            $gimcana->estado = 'en_progreso';
            $gimcana->save();

            return response()->json([
                'success' => true,
                'message' => 'Gimcana iniciada correctamente'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al iniciar la gimcana: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al iniciar la gimcana: ' . $e->getMessage()
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

            $usuario = Auth::user();
            
            // Actualizar la ubicación del usuario
            DB::table('usuarios')
                ->where('id', $usuario->id)
                ->update([
                    'ubicacion_actual' => DB::raw("POINT({$request->longitud}, {$request->latitud})"),
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Posición actualizada correctamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar posición: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la posición'
            ], 500);
        }
    }

    public function obtenerPosicionesUsuarios($gimcana_id)
    {
        try {
            $gimcana = Gimcana::findOrFail($gimcana_id);
            
            // Obtener todos los usuarios de los grupos de la gimcana con sus ubicaciones
            $usuarios = DB::table('usuarios')
                ->join('usuarios_grupos', 'usuarios.id', '=', 'usuarios_grupos.usuario_id')
                ->join('grupos', 'usuarios_grupos.grupo_id', '=', 'grupos.id')
                ->join('gimcana_grupo', 'grupos.id', '=', 'gimcana_grupo.grupo_id')
                ->where('gimcana_grupo.gimcana_id', $gimcana_id)
                ->select([
                    'usuarios.id',
                    'usuarios.nombre',
                    DB::raw('ST_X(ubicacion_actual) as longitud'),
                    DB::raw('ST_Y(ubicacion_actual) as latitud'),
                    'grupos.nombre as grupo_nombre',
                    'grupos.id as grupo_id'
                ])
                ->get();

            return response()->json([
                'success' => true,
                'usuarios' => $usuarios->map(function($usuario) {
                    return [
                        'id' => $usuario->id,
                        'nombre' => $usuario->nombre,
                        'latitud' => (float)$usuario->latitud,
                        'longitud' => (float)$usuario->longitud,
                        'grupo' => [
                            'id' => $usuario->grupo_id,
                            'nombre' => $usuario->grupo_nombre
                        ],
                        'color' => '#' . substr(md5($usuario->grupo_id), 0, 6) // Color único por grupo
                    ];
                })
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener posiciones de usuarios: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las posiciones de los usuarios'
            ], 500);
        }
    }
}
