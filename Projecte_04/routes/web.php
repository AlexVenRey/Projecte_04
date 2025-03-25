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
        
        // Rutas para marcadores de usuario
        Route::get('/marcadores/crear', [UserMakerController::class, 'create'])->name('cliente.marcadores.create');
        Route::post('/marcadores', [UserMakerController::class, 'store'])->name('cliente.marcadores.store');
        Route::delete('/marcadores/{lugar}', [UserMakerController::class, 'destroy'])->name('cliente.marcadores.destroy');
    });
});

// Ruta de logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/')->with('success', 'Sesión cerrada correctamente.');
})->name('logout');