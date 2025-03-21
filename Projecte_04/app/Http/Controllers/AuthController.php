<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Intentamos encontrar al usuario por su email
        $user = User::where('email', $credentials['email'])->first();

        // Verificamos si el usuario existe y si la contraseña es correcta
        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Si la contraseña es correcta, iniciamos la sesión
            Auth::login($user);

            // Verificar el rol del usuario y redirigir
            if ($user->rol === 'admin') {
                return redirect()->route('admin.index');
            } elseif ($user->rol === 'usuario') {
                return redirect()->route('cliente.index');
            }
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas.'])->withInput();
    }

    public function showRegister()
    {
        // Retorna la vista de registro
        return view('register.register');
    }

    public function register(Request $request)
    {
        // Validar los datos del registro
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Crear el usuario
        $user = User::create([
            'nombre' => $validated['nombre'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'rol' => 'usuario',  // Asigna el rol de usuario por defecto
        ]);

        // Iniciar sesión con el nuevo usuario
        Auth::login($user);

        // Redirigir a la página del cliente después del registro
        return redirect()->route('cliente.index');
    }
}
