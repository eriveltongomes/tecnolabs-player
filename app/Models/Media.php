<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    use HasFactory;

    // Define os campos que podem ser salvos no banco
    protected $fillable = [
        'project_id', 
        'media_category_id', 
        'file_type', 
        'path', 
        'description',
        'map_x', // Caso use pinos no futuro
        'map_y'  // Caso use pinos no futuro
    ];

    // Relacionamento: Uma mídia pertence a um Projeto
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // Relacionamento: Uma mídia pertence a uma Categoria
    public function category(): BelongsTo
    {
        return $this->belongsTo(MediaCategory::class, 'media_category_id');
    }
}