<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MediaCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'type',
        'sort_order',
        'custom_path' // <--- Adicionado
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function mediaFiles(): HasMany
    {
        return $this->hasMany(MediaFile::class);
    }
}