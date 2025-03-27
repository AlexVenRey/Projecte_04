<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LugarController;
use App\Http\Controllers\EtiquetaController;
use App\Http\Controllers\GimcanaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UserMakerController;
use Illuminate\Http\Request;
use App\Models\Lugar;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ClienteGimcanaController;
use App\Http\Controllers\ClienteGrupoController;

// Rutas públicas
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

// Rutas autenticadas
Route::middleware(['auth'])->group(function () {
    // Rutas del admin
    Route::get('/admin/index', [LugarController::class, 'showMap'])->name('admin.index');
    // ... otras rutas de admin
    
    // Rutas del cliente
    Route::prefix('cliente')->group(function () {
        Route::get('/index', [ClienteController::class, 'index'])->name('cliente.index');
        Route::get('/lugares', [ClienteController::class, 'getLugares']);
        Route::get('/etiquetas', [ClienteController::class, 'getEtiquetas']);
        Route::get('/favoritos', [ClienteController::class, 'getFavoritos']);
        Route::post('/favoritos/{lugar}', [ClienteController::class, 'toggleFavorito']);
        Route::post('/lugares/cercanos', [ClienteController::class, 'buscarCercanos']);
        Route::post('/puntos', [ClienteController::class, 'storePunto']);

        // Ruta para obtener los grupos y sus miembros
        Route::get('/grupos/{gimcana_id}/miembros', [ClienteGrupoController::class, 'obtenerGrupos'])
            ->name('cliente.grupos.miembros');
        
        Route::post('/crear-grupo', [ClienteGrupoController::class, 'crearGrupo'])
            ->name('cliente.crear-grupo');
        Route::post('/unirse-grupo', [ClienteGrupoController::class, 'unirseGrupo'])
            ->name('cliente.unirse-grupo');
        
        // Mantener la ruta de gimcanas dentro del grupo de cliente
        Route::get('/gimcanas', [ClienteGimcanaController::class, 'index'])->name('cliente.gimcanas');

        Route::get('/mis-favoritos', [ClienteController::class, 'misFavoritos'])->name('cliente.mis-favoritos');
        Route::post('/toggle-favorito', [ClienteController::class, 'toggleFavorito'])->name('cliente.toggle-favorito');
    });
});

// Ruta de logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/')->with('success', 'Sesión cerrada correctamente.');
})->name('logout');

// Ruta para editar gimcana
Route::get('admin/gimcana/{id}/edit', [GimcanaController::class, 'edit'])->name('admin.gimcana.edit');
Route::put('admin/gimcana/{id}', [GimcanaController::class, 'update'])->name('admin.gimcana.update');

// Ruta para almacenar puntos del cliente
Route::post('/cliente/puntos', [ClienteController::class, 'storePunto'])->middleware('auth');