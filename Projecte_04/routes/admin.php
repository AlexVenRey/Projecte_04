<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LugarController;
use App\Http\Controllers\Admin\EtiquetaController;
use App\Http\Controllers\Admin\PruebaController;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rutas para lugares
    Route::resource('lugares', LugarController::class);
    
    // Rutas para etiquetas
    Route::resource('etiquetas', EtiquetaController::class);
    
    // Rutas para pruebas de gimcana
    Route::resource('pruebas', PruebaController::class);
    
    // API Routes para AJAX
    Route::prefix('api')->group(function () {
        // Lugares
        Route::get('/lugares', [LugarController::class, 'getLugares']);
        Route::get('/lugares/etiqueta/{etiqueta}', [LugarController::class, 'getLugarByEtiqueta']);
        Route::get('/lugares/cercanos', [LugarController::class, 'getLugaresCercanos']);
        
        // Pruebas
        Route::get('/pruebas/grupo/{grupo}', [PruebaController::class, 'getPruebasByGrupo']);
        Route::get('/pruebas/{prueba}/grupo/{grupo}/verificar', [PruebaController::class, 'verificarCompletitud']);
    });
});
