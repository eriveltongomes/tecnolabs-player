<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'block',
        'unit_number',
        'floor',
        'typology',
        'area',
        'price',
        'status',
        'floorplan_image',
        'map_x',
        'map_y'
    ];

    // Uma unidade pertence a um Projeto
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}