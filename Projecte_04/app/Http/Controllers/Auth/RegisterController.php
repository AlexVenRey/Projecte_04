<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:usuarios',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => hash('sha256', $request->password),
            'rol' => 'usuario', // Por defecto, todos los nuevos usuarios son usuarios normales
        ]);

        Auth::login($user);

        return redirect()->route('user.dashboard');
    }
}
