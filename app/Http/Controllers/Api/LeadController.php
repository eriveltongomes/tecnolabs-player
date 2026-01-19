<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validação simples
        $validator = Validator::make($request->all(), [
            'project_slug' => 'required|exists:projects,slug',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'message' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        // 2. Encontra o ID do projeto pelo slug
        $project = Project::where('slug', $request->project_slug)->first();

        // 3. Cria o Lead
        $lead = Lead::create([
            'project_id' => $project->id,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'message' => $request->message ?? 'Tenho interesse no empreendimento.',
            'status' => 'new'
        ]);

        return response()->json(['success' => true, 'message' => 'Recebemos seu contato!'], 201);
    }
}