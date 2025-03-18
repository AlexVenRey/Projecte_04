<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Ruta de inicio (login)
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Ruta de registro (no se modifica)
Route::get('/register', function () {
    return view('register.register');
});

// Ruta del admin (index)
Route::get('/admin/index', function () {
    return view('admin.index'); // Ruta correcta para el archivo admin.index.blade.php
})->name('admin.index');  // Le damos un nombre a la ruta para redirigir correctamente

// Ruta del cliente (index)
Route::get('/cliente/index', function () {
    return view('cliente.index'); // Ruta correcta para el archivo cliente.index.blade.php
})->name('cliente.index');  // Le damos un nombre a la ruta para redirigir correctamente
