<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LugarController;
use App\Http\Controllers\EtiquetaController;
use App\Http\Controllers\GimcanaController;

// Ruta de inicio (login)
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Ruta de registro (no se modifica)
Route::get('/register', function () {
    return view('register.register');
});

// Ruta del admin (index)
Route::get('/admin/index', [LugarController::class, 'showMap'])->name('admin.index');
Route::get('/admin/gimcana', [GimcanaController::class, 'index'])->name('admin.gimcana');

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

// Ruta para crear gimcana
Route::get('/admin/creargimcana', [GimcanaController::class, 'create'])->name('admin.creargimcana');
Route::post('/admin/creargimcana', [GimcanaController::class, 'store'])->name('admin.creargimcana.store');

// Ruta para editar gimcana (nueva ruta añadida)
Route::get('/admin/gimcana/{gimcana}/editar', [GimcanaController::class, 'edit'])->name('admin.gimcana.edit');

// Ruta para actualizar gimcana (nueva ruta añadida)
Route::put('/admin/gimcana/{gimcana}', [GimcanaController::class, 'update'])->name('admin.gimcana.update');

// Ruta para eliminar gimcana (nueva ruta añadida)
Route::delete('/admin/gimcana/{gimcana}', [GimcanaController::class, 'destroy'])->name('admin.gimcana.delete');
