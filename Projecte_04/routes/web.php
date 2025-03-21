<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LugarController;
use App\Http\Controllers\EtiquetaController;
use App\Http\Controllers\GimcanaController;
use Illuminate\Http\Request;
use App\Models\Lugar;
use Illuminate\Support\Facades\Auth;


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
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/index', [LugarController::class, 'showMap'])->name('admin.index');
    Route::get('/admin/puntos', [LugarController::class, 'index'])->name('admin.puntos');
});


// Ruta del cliente (index)
Route::get('/cliente/index', function () {
    return view('cliente.index');
})->name('cliente.index');

// Ruta para puntos de interés
Route::post('/admin/puntos', [LugarController::class, 'store'])->name('admin.puntos.store');

// Ruta para añadir punto de interés
Route::get('/admin/añadirpunto', function () {
    $etiquetas = App\Models\Etiqueta::all();
    return view('admin.añadirpunto', compact('etiquetas'));
})->name('admin.añadirpunto');


// Ruta para crear gimcana
Route::get('/admin/creargimcana', [GimcanaController::class, 'create'])->name('admin.creargimcana');
Route::post('/admin/creargimcana', [GimcanaController::class, 'store'])->name('admin.creargimcana.store');

// Rutas para editar y actualizar puntos de interés
Route::get('/admin/puntos/{id}/edit', [LugarController::class, 'edit'])->name('admin.puntos.edit');
Route::put('/admin/puntos/{id}', [LugarController::class, 'update'])->name('admin.puntos.update');
Route::delete('/admin/puntos/{id}', [LugarController::class, 'destroy'])->name('admin.puntos.destroy');

Route::get('/admin/puntos/check-nombre', function (Request $request) {
    $exists = Lugar::where('nombre', $request->query('nombre'))
        ->where('id', '!=', $request->query('id')) // Excluir el registro actual
        ->exists();
    return response()->json(['exists' => $exists]);
});

Route::post('/logout', function () {
    Auth::logout(); // Cerrar la sesión del usuario
    return redirect('/')->with('success', 'Sesión cerrada correctamente.');
})->name('logout');


// Ruta para editar gimcana (nueva ruta añadida)
Route::get('/admin/gimcana/{gimcana}/editar', [GimcanaController::class, 'edit'])->name('admin.gimcana.edit');

// Ruta para actualizar gimcana (nueva ruta añadida)
Route::put('/admin/gimcana/{gimcana}', [GimcanaController::class, 'update'])->name('admin.gimcana.update');

// Ruta para eliminar gimcana (nueva ruta añadida)
Route::delete('/admin/gimcana/{gimcana}', [GimcanaController::class, 'destroy'])->name('admin.gimcana.delete');

// Ruta para mostrar el formulario de registro
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

// Ruta para registrar el usuario
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
