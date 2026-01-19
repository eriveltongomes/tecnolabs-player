<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Models\Project; // <--- IMPORTANTE: Importar o Model

Route::get('/', function () {
    // 1. Busca os dados do projeto "algarve" (título, cores, logo)
    $project = Project::where('slug', 'algarve')->firstOrFail();

    // 2. Chama a view que agora está na pasta certa
    return view('projects.algarve.intro', compact('project'));
});

// ... Mantenha as outras rotas (API, etc) abaixo ...
Route::get('/api/project/{slug}', [ProjectController::class, 'api']);
Route::get('/{slug}', [ProjectController::class, 'intro'])->name('project.intro');
Route::get('/{slug}/app', [ProjectController::class, 'app'])->name('project.app');