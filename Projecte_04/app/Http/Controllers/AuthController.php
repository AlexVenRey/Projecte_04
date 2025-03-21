<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Ruta del archivo login.blade.php
        return view('login.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Intentamos encontrar al usuario por su email
        $user = User::where('email', $credentials['email'])->first();

        // Verificamos si el usuario existe y si la contraseña SHA-256 es correcta
        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Si la contraseña es correcta, iniciamos la sesión
            Auth::login($user);

            // Verificar el rol del usuario y redirigir
            if ($user->rol === 'admin') {
                // Redirigir a la página de admin si el rol es 'admin'
                return redirect()->route('admin.index');
            } elseif ($user->rol === 'usuario') {
                // Redirigir a la página de cliente si el rol es 'usuario'
                return redirect()->route('cliente.index');
            }
        }

        // Si las credenciales no son correctas, mostramos un error
        return back()->withErrors(['email' => 'Credenciales incorrectas.'])->withInput();
    }
}
