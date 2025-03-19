<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LugarController;

// Ruta de inicio (login)
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Ruta de registro (no se modifica)
Route::get('/register', function () {
    return view('register.register');
});

// Ruta del admin (index)
Route::get('/admin/index', [LugarController::class, 'showMap'])->name('admin.index');

// Ruta del cliente (index)
Route::get('/cliente/index', function () {
    return view('cliente.index');
})->name('cliente.index');

// Ruta para puntos de interés
Route::get('/admin/puntos', [LugarController::class, 'index'])->name('admin.puntos');
