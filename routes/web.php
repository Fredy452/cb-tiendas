<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TiendaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tiendas', [TiendaController::class, 'index'])->name('tiendas.index');
Route::get('/categorias', [TiendaController::class, 'categorias'])->name('categorias');
