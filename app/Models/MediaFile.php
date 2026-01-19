<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'media_category_id', // O vínculo com a categoria
        'file_type',         // 'image' ou 'video'
        'path',              // O arquivo
        'active'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function category(): BelongsTo
    {
        // Atenção aqui: o nome do model é MediaCategory
        return $this->belongsTo(MediaCategory::class, 'media_category_id');
    }
}