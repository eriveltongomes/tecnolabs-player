<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;

Route::get('/', function () {
    return view('welcome');
});

// 1. Rota da API (Fornece os dados JSON para o Javascript do Masterplan)
Route::get('/api/project/{slug}', [ProjectController::class, 'api']);

// 2. Rota da CAPA (Intro) - Ex: meudominio.com/algarve
Route::get('/{slug}', [ProjectController::class, 'intro'])->name('project.intro');

// 3. Rota do SISTEMA (App) - Ex: meudominio.com/algarve/app
Route::get('/{slug}/app', [ProjectController::class, 'app'])->name('project.app');