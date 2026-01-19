<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Media; // <--- ADICIONE ESTA LINHA IMPORTANTE

class MediaCategory extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'name', 'type'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }
}