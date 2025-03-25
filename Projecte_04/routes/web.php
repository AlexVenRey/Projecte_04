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

// Ruta del cliente (index)
Route::get('/cliente/index', [ClienteController::class, 'index'])->name('cliente.index');

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

    // Rutas para gimcanas
    Route::get('/admin/gimcana', [GimcanaController::class, 'index'])->name('admin.gimcana');
    Route::get('/admin/creargimcana', [GimcanaController::class, 'create'])->name('admin.creargimcana');
    Route::post('/admin/creargimcana', [GimcanaController::class, 'store'])->name('admin.creargimcana.store');
    Route::get('/admin/gimcana/{gimcana}/editar', [GimcanaController::class, 'edit'])->name('admin.gimcana.edit');
    Route::put('/admin/gimcana/{gimcana}', [GimcanaController::class, 'update'])->name('admin.gimcana.update');
    Route::delete('/admin/gimcana/{gimcana}', [GimcanaController::class, 'destroy'])->name('admin.gimcana.delete');

    // Rutas del cliente
    Route::middleware(['auth'])->prefix('cliente')->group(function () {
        Route::get('/index', [ClienteController::class, 'index'])->name('cliente.index');
        Route::get('/lugares', [ClienteController::class, 'getLugares']);
        Route::get('/etiquetas', [ClienteController::class, 'getEtiquetas']);
        Route::get('/favoritos', [ClienteController::class, 'getFavoritos']);
        Route::post('/favoritos/{lugar}', [ClienteController::class, 'toggleFavorito']);
        Route::post('/lugares/cercanos', [ClienteController::class, 'buscarCercanos']);
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

// Ruta para editar gimcana
Route::get('admin/gimcana/{id}/edit', [GimcanaController::class, 'edit'])->name('admin.gimcana.edit');
Route::put('admin/gimcana/{id}', [GimcanaController::class, 'update'])->name('admin.gimcana.update');