<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/dashboard'); // Cambia esto por la página deseada
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas.'])->withInput();
    }
}
