<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LugarController;
use App\Http\Controllers\EtiquetaController;
use App\Http\Controllers\GimcanaController;
use App\Http\Controllers\ClienteController;
use Illuminate\Http\Request;
use App\Models\Lugar;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ClienteGimcanaController;
use App\Http\Controllers\ClienteGrupoController;

// Ruta de inicio (login)
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Ruta de registro
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    // Rutas del admin
    Route::get('/admin/index', [LugarController::class, 'showMap'])->name('admin.index');
    
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

    // Rutas para gimcanas del admin
    Route::get('/admin/gimcana', [GimcanaController::class, 'index'])->name('admin.gimcana');
    Route::get('/admin/creargimcana', [GimcanaController::class, 'create'])->name('admin.creargimcana');
    Route::post('/admin/creargimcana', [GimcanaController::class, 'store'])->name('admin.creargimcana.store');
    Route::get('/admin/gimcana/{gimcana}/editar', [GimcanaController::class, 'edit'])->name('admin.gimcana.edit');
    Route::put('/admin/gimcana/{gimcana}', [GimcanaController::class, 'update'])->name('admin.gimcana.update');
    Route::delete('/admin/gimcana/{gimcana}', [GimcanaController::class, 'destroy'])->name('admin.gimcana.delete');
    Route::get('admin/gimcana/{id}/edit', [GimcanaController::class, 'edit'])->name('admin.gimcana.edit');
    Route::put('admin/gimcana/{id}', [GimcanaController::class, 'update'])->name('admin.gimcana.update');

    // Todas las rutas del cliente agrupadas
    Route::prefix('cliente')->group(function () {
        // Rutas básicas del cliente
        Route::get('/index', [ClienteController::class, 'index'])->name('cliente.index');
        Route::get('/lugares', [ClienteController::class, 'getLugares']);
        Route::get('/etiquetas', [ClienteController::class, 'getEtiquetas']);
        Route::get('/favoritos', [ClienteController::class, 'getFavoritos']);
        Route::post('/favoritos/{lugar}', [ClienteController::class, 'toggleFavorito']);
        Route::post('/lugares/cercanos', [ClienteController::class, 'buscarCercanos']);
        Route::post('/puntos', [ClienteController::class, 'storePunto']);

        // Rutas para gimcanas del cliente
        Route::get('/gimcanas', [ClienteGimcanaController::class, 'index'])->name('cliente.gimcanas');
        Route::get('/gimcanas/{gimcana_id}/lugares', [ClienteGimcanaController::class, 'getLugares']);
        Route::get('/gimcanas/{gimcana_id}/live', [ClienteGimcanaController::class, 'live'])->name('cliente.gimcanas.live');
        Route::get('/gimcanas/{gimcana_id}/verificar-listos', [ClienteGrupoController::class, 'verificarTodosListos']);
        Route::post('/gimcanas/{gimcana_id}/iniciar', [ClienteGrupoController::class, 'iniciarGimcana']);

        // Rutas para grupos
        Route::get('/grupos/{gimcana_id}/miembros', [ClienteGrupoController::class, 'obtenerGrupos'])->name('cliente.grupos.miembros');
        Route::post('/grupos/crear-grupo', [ClienteGrupoController::class, 'crearGrupo'])->name('cliente.crear-grupo');
        Route::post('/grupos/unirse-grupo', [ClienteGrupoController::class, 'unirseGrupo'])->name('cliente.unirse-grupo');
        Route::post('/grupos/marcar-listo', [ClienteGrupoController::class, 'marcarListo'])->name('cliente.marcar-listo');

        // Rutas para favoritos
        Route::get('/mis-favoritos', [ClienteController::class, 'misFavoritos'])->name('cliente.mis-favoritos');
        Route::post('/toggle-favorito', [ClienteController::class, 'toggleFavorito'])->name('cliente.toggle-favorito');
    });
});

// Ruta para verificar nombre único de puntos de interés
Route::get('/admin/puntos/check-nombre', function (Request $request) {
    $exists = Lugar::where('nombre', $request->query('nombre'))
        ->where('id', '!=', $request->query('id'))
        ->exists();
    return response()->json(['exists' => $exists]);
});

// Ruta para cerrar sesión
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/')->with('success', 'Sesión cerrada correctamente.');
})->name('logout');