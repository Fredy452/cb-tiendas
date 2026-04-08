<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TiendaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tiendas', [TiendaController::class, 'index'])->name('tiendas.index');
