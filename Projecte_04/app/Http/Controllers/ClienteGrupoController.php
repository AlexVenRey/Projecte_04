<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grupo;
use App\Models\Gimcana;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ClienteGrupoController extends Controller
{
    public function crearGrupo(Request $request)
    {
        try {
            // Validaciones detalladas
            $validator = Validator::make($request->all(), [
                'gimcana_id' => 'required|exists:gimcanas,id',
                'nombre' => [
                    'required',
                    'string',
                    'max:100',
                    'min:3',
                    'regex:/^[a-zA-Z0-9\s]+$/' // Solo letras, números y espacios
                ]
            ], [
                'nombre.required' => 'El nombre del grupo es obligatorio',
                'nombre.min' => 'El nombre debe tener al menos 3 caracteres',
                'nombre.max' => 'El nombre no puede tener más de 100 caracteres',
                'nombre.regex' => 'El nombre solo puede contener letras, números y espacios',
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

            // Verificar si el usuario ya está en un grupo en esta gimcana
            $usuario = User::find(Auth::id());
            $usuarioTieneGrupo = $usuario->grupos()
                ->whereHas('gimcanas', function($query) use ($request) {
                    $query->where('gimcanas.id', $request->gimcana_id);
                })->exists();

            if ($usuarioTieneGrupo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya perteneces a un grupo en esta gimcana'
                ], 422);
            }

            // Verificar si ya existe un grupo con ese nombre en la gimcana
            $grupoExistente = Gimcana::find($request->gimcana_id)
                ->grupos()
                ->where('nombre', $request->nombre)
                ->exists();

            if ($grupoExistente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya existe un grupo con ese nombre. Por favor, elige otro nombre.'
                ], 422);
            }

            // Crear el grupo
            $grupo = new Grupo();
            $grupo->nombre = trim($request->nombre); // Eliminar espacios en blanco
            $grupo->descripcion = "Grupo para la gimcana " . $request->gimcana_id;
            $grupo->save();

            // Asociar el grupo con la gimcana
            $grupo->gimcanas()->attach($request->gimcana_id);

            // Añadir al usuario creador al grupo
            $grupo->usuarios()->attach($usuario->id);

            return response()->json([
                'success' => true,
                'message' => 'Grupo creado exitosamente',
                'grupo' => $grupo
            ]);

        } catch (\Exception $e) {
            Log::error('Error al crear grupo: ' . $e->getMessage());
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

            // Verificar el número máximo de usuarios por grupo (opcional)
            $grupo = Grupo::find($request->grupo_id);
            if ($grupo->usuarios()->count() >= 5) { // Por ejemplo, máximo 5 usuarios por grupo
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

    public function obtenerGrupos($gimcana_id)
    {
        try {
            $gimcana = Gimcana::findOrFail($gimcana_id);
            
            // Obtener grupos con sus usuarios
            $grupos = $gimcana->grupos()
                ->with(['usuarios' => function($query) {
                    $query->select('usuarios.id', 'usuarios.nombre');
                }])
                ->get();

            // Log para debug usando la fachada importada
            Log::info('Grupos obtenidos:', [
                'gimcana_id' => $gimcana_id, 
                'grupos' => $grupos->toArray()
            ]);

            return response()->json([
                'success' => true,
                'grupos' => $grupos
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener grupos: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los grupos: ' . $e->getMessage()
            ], 500);
        }
    }
}
