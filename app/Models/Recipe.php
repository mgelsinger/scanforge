<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recipe extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'inputs' => 'array',
        'outputs' => 'array',
        'metadata' => 'array',
    ];

    public function requiredBlueprint(): BelongsTo
    {
        return $this->belongsTo(Blueprint::class, 'required_blueprint_id');
    }
}
