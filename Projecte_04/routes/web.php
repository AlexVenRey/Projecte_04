<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Incluir rutas de autenticación
require __DIR__.'/auth.php';

// Rutas de administrador
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'rol:admin'])
    ->group(base_path('routes/admin.php'));

// Rutas de usuario normal
Route::middleware(['auth', 'rol:usuario'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('user.dashboard');
        })->name('dashboard');
    });
