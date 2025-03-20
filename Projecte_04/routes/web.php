<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LugarController;
use App\Http\Controllers\EtiquetaController;

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
Route::post('/admin/puntos', [LugarController::class, 'store'])->name('admin.puntos.store');

// Ruta para añadir punto de interés
Route::get('/admin/añadirpunto', function () {
    $etiquetas = App\Models\Etiqueta::all();
    return view('admin.añadirpunto', compact('etiquetas'));
})->name('admin.añadirpunto');


