<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TiendaController;

Route::get('/', [TiendaController::class, 'home'])->name('home');
Route::get('/tiendas', [TiendaController::class, 'index'])->name('tiendas.index');
Route::get('/tiendas/{store}', [TiendaController::class, 'show'])->name('tiendas.show');
Route::get('/categorias', [TiendaController::class, 'categorias'])->name('categorias');
Route::get('/emprendimientos/registrar', [TiendaController::class, 'create'])->name('emprendimientos.create');
Route::post('/emprendimientos/registrar', [TiendaController::class, 'store'])->name('emprendimientos.store');
Route::get('/sobre-nosotros', [TiendaController::class, 'about'])->name('sobre-nosotros');
