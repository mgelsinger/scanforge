<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Blueprint extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'metadata' => 'array',
        'is_completed' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fragments(): HasMany
    {
        return $this->hasMany(BlueprintFragment::class);
    }

    public function forgedUnits(): HasMany
    {
        return $this->hasMany(ForgedUnit::class);
    }

    public function gearItems(): HasMany
    {
        return $this->hasMany(GearItem::class);
    }

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class, 'required_blueprint_id');
    }
}
