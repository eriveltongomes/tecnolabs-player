<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Project; 

class ProjectController extends Controller
{
    // RETORNA OS DADOS JSON (USADO PELO JAVASCRIPT)
    public function api($slug)
    {
        $project = DB::table('projects')->where('slug', $slug)->first();

        if (!$project) {
            return response()->json(['error' => 'Projeto não encontrado'], 404);
        }

        $categories = DB::table('media_categories')
            ->where('project_id', $project->id)
            ->orderBy('sort_order')
            ->get();

        $media = DB::table('media_files')
            ->whereIn('media_category_id', $categories->pluck('id'))
            ->orderBy('sort_order')
            ->get();
            
        $units = DB::table('units')
            ->where('project_id', $project->id)
            ->get();

        $project->theme_config = json_decode($project->theme_config);

        return response()->json([
            'project' => $project,
            'categories' => $categories,
            'media' => $media,
            'units' => $units
        ]);
    }

    // MOSTRA A TELA DE INTRO (CAPA)
    public function intro($slug)
    {
        $project = Project::where('slug', $slug)->where('active', true)->firstOrFail();
        
        return view('projects.intro', compact('project'));
    }

    // MOSTRA O SISTEMA PRINCIPAL (APP)
    public function app($slug)
    {
        $project = Project::where('slug', $slug)->where('active', true)->firstOrFail();
        
        // 1. Tenta achar uma view específica: projects/nomedoslug/master.blade.php
        $viewName = "projects.{$slug}.master";
        
        // 2. Se não existir, usa a view padrão do sistema (Algarve)
        if (!view()->exists($viewName)) {
            $viewName = "projects.algarve.master"; 
        }

        return view($viewName, compact('project'));
    }
}