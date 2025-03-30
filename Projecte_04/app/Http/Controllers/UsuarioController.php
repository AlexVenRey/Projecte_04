<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        return view('admin.usuarios', compact('usuarios')); // Cambiado a 'admin.usuarios'
    }

    public function create()
    {
        return view('admin.crearusuarios'); // Cambiado a 'admin.crearusuarios'
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:8|confirmed',
            'rol' => 'required|in:usuario,admin',
        ]);

        DB::transaction(function () use ($request) {
            User::create([
                'nombre' => $request->nombre,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rol' => $request->rol,
            ]);
        });

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        return view('admin.editarusuarios', compact('usuario')); // Cambiado a 'admin.editarusuarios'
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'rol' => 'required|in:usuario,admin',
        ]);

        DB::transaction(function () use ($request, $id) {
            $usuario = User::findOrFail($id);

            $usuario->update([
                'nombre' => $request->nombre,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $usuario->password,
                'rol' => $request->rol,
            ]);
        });

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $usuario = User::findOrFail($id);
            $usuario->delete();
        });

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
