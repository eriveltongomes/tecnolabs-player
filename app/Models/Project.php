<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'city',
        'facade_image',
        'intro_image', // Campo novo da intro
        'theme_config',
        'active',
    ];

    protected $casts = [
        'theme_config' => 'array',
        'active' => 'boolean',
    ];

    // Relação com Unidades
    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    // Relação com Categorias de Mídia (Álbuns)
    // ATENÇÃO: O nome aqui é 'mediaCategories' (camelCase)
    public function mediaCategories()
{
    return $this->hasMany(MediaCategory::class);
}

    // Relação com Arquivos de Mídia (Fotos/Vídeos)
    public function media()
{
    return $this->hasMany(Media::class);
}
}