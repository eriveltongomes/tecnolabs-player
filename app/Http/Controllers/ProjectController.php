<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // 1. API: Retorna o JSON para o Javascript (Masterplan e Unidades)
    public function api($slug)
    {
        // CORREÇÃO AQUI: Mudamos de 'categories.media' para 'mediaCategories.media'
        // Baseado no seu código do Filament, o relacionamento se chama 'mediaCategories'
        $project = Project::where('slug', $slug)
            ->where('active', true)
            ->with(['units', 'mediaCategories.media']) 
            ->firstOrFail();

        return response()->json([
            'project' => $project,
            'units' => $project->units,
            // CORREÇÃO AQUI: O frontend espera 'categories', então enviamos o conteúdo de 'mediaCategories' dentro dessa chave
            'categories' => $project->mediaCategories, 
            'media' => $project->media, 
        ]);
    }

    // 2. CAPA (INTRO): Tela de boas-vindas
    public function intro($slug)
    {
        $project = Project::where('slug', $slug)->where('active', true)->firstOrFail();
        
        // Procura: projects/algarve/intro.blade.php
        $viewName = "projects.{$slug}.intro";

        if (view()->exists($viewName)) {
            return view($viewName, compact('project'));
        }

        // Fallback
        if (view()->exists('projects.intro')) {
            return view('projects.intro', compact('project'));
        }

        abort(404, "Intro não encontrada.");
    }

    // 3. APP PRINCIPAL: O Painel Interativo
    public function app($slug)
    {
        $project = Project::where('slug', $slug)->where('active', true)->firstOrFail();
        
        // Procura: projects/algarve/master.blade.php
        $viewName = "projects.{$slug}.master";
        
        if (view()->exists($viewName)) {
            return view($viewName, compact('project'));
        }

        return view('projects.master', compact('project'));
    }
}