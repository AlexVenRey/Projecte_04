<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LugarController;
use App\Http\Controllers\EtiquetaController;
use App\Http\Controllers\GimcanaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UsuarioController;

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
    
    // Rutas para puntos de interés
    Route::get('/admin/puntos', [LugarController::class, 'index'])->name('admin.puntos');
    Route::post('/admin/puntos', [LugarController::class, 'store'])->name('admin.puntos.store');
    Route::get('/admin/añadirpunto', function () {
        $etiquetas = App\Models\Etiqueta::all();
        return view('admin.añadirpunto', compact('etiquetas'));
    })->name('admin.añadirpunto');
    Route::get('/admin/puntos/{id}/edit', [LugarController::class, 'edit'])->name('admin.puntos.edit');
    Route::put('/admin/puntos/{id}', [LugarController::class, 'update'])->name('admin.puntos.update');
    Route::delete('/admin/puntos/{id}', [LugarController::class, 'destroy'])->name('admin.puntos.destroy');

    // Rutas para etiquetas
    Route::get('/admin/etiquetas', [EtiquetaController::class, 'index'])->name('admin.etiquetas');
    Route::get('/admin/etiquetas/crear', [EtiquetaController::class, 'create'])->name('admin.etiquetas.create');
    Route::post('/admin/etiquetas', [EtiquetaController::class, 'store'])->name('admin.etiquetas.store');
    Route::get('/admin/etiquetas/{etiqueta}/edit', [EtiquetaController::class, 'edit'])->name('admin.etiquetas.edit');
    Route::put('/admin/etiquetas/{etiqueta}', [EtiquetaController::class, 'update'])->name('admin.etiquetas.update');
    Route::delete('/admin/etiquetas/{etiqueta}', [EtiquetaController::class, 'destroy'])->name('admin.etiquetas.destroy');

    // Rutas para gimcanas
    Route::get('/admin/gimcana', [GimcanaController::class, 'index'])->name('admin.gimcana');
    Route::get('/admin/creargimcana', [GimcanaController::class, 'create'])->name('admin.creargimcana');
    Route::post('/admin/creargimcana', [GimcanaController::class, 'store'])->name('admin.creargimcana.store');
    Route::get('/admin/gimcana/{gimcana}/editar', [GimcanaController::class, 'edit'])->name('admin.gimcana.edit');
    Route::put('/admin/gimcana/{gimcana}', [GimcanaController::class, 'update'])->name('admin.gimcana.update');
    Route::delete('/admin/gimcana/{gimcana}', [GimcanaController::class, 'destroy'])->name('admin.gimcana.delete');

    // Rutas para usuarios
    Route::resource('admin/usuarios', UsuarioController::class)->names([
        'index' => 'admin.usuarios.index',
        'create' => 'admin.usuarios.create',
        'store' => 'admin.usuarios.store',
        'edit' => 'admin.usuarios.edit',
        'update' => 'admin.usuarios.update',
        'destroy' => 'admin.usuarios.destroy',
    ]);

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