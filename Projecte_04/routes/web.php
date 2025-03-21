<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LugarController;
use App\Http\Controllers\EtiquetaController;
use App\Http\Controllers\GimcanaController;
use App\Http\Controllers\ClienteController; // Añadimos el controlador del cliente
use Illuminate\Http\Request;
use App\Models\Lugar;
use Illuminate\Support\Facades\Auth;


// Ruta del cliente (index)
Route::get('/cliente/index', [ClienteController::class, 'index'])->name('cliente.index');
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

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    // Rutas del admin
    Route::get('/admin/index', [LugarController::class, 'showMap'])->name('admin.index');
    Route::get('/admin/puntos', [LugarController::class, 'index'])->name('admin.puntos');
    Route::post('/admin/puntos', [LugarController::class, 'store'])->name('admin.puntos.store');
    Route::get('/admin/añadirpunto', function () {
        $etiquetas = App\Models\Etiqueta::all();
        return view('admin.añadirpunto', compact('etiquetas'));
    })->name('admin.añadirpunto');
    Route::get('/admin/puntos/{id}/edit', [LugarController::class, 'edit'])->name('admin.puntos.edit');
    Route::put('/admin/puntos/{id}', [LugarController::class, 'update'])->name('admin.puntos.update');
    Route::delete('/admin/puntos/{id}', [LugarController::class, 'destroy'])->name('admin.puntos.destroy');

    // Rutas para gimcanas
    Route::get('/admin/creargimcana', [GimcanaController::class, 'create'])->name('admin.creargimcana');
    Route::post('/admin/creargimcana', [GimcanaController::class, 'store'])->name('admin.creargimcana.store');
    Route::get('/admin/gimcana/{gimcana}/editar', [GimcanaController::class, 'edit'])->name('admin.gimcana.edit');
    Route::put('/admin/gimcana/{gimcana}', [GimcanaController::class, 'update'])->name('admin.gimcana.update');
    Route::delete('/admin/gimcana/{gimcana}', [GimcanaController::class, 'destroy'])->name('admin.gimcana.delete');

    // Ruta del cliente (dashboard)
    Route::get('/cliente/index', [ClienteController::class, 'index'])->name('cliente.index');
});

// Ruta para verificar nombre único de puntos de interés
Route::get('/admin/puntos/check-nombre', function (Request $request) {
    $exists = Lugar::where('nombre', $request->query('nombre'))
        ->where('id', '!=', $request->query('id')) // Excluir el registro actual
        ->exists();
    return response()->json(['exists' => $exists]);
});

// Ruta para cerrar sesión
Route::post('/logout', function () {
    Auth::logout(); // Cerrar la sesión del usuario
    return redirect('/')->with('success', 'Sesión cerrada correctamente.');
})->name('logout');