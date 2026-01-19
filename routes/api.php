<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LeadController; // <--- AQUI SIM É O LUGAR CORRETO!
use App\Http\Controllers\ProjectController; 

// Rota para buscar os dados do projeto (JSON)
Route::get('/project/{slug}', [ProjectController::class, 'show']);

// Rota para salvar os Leads (O formulário envia para cá)
Route::post('/leads', [LeadController::class, 'store']);