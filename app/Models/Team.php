<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function teamUnits(): HasMany
    {
        return $this->hasMany(TeamUnit::class);
    }

    public function forgedUnits(): BelongsToMany
    {
        return $this->belongsToMany(ForgedUnit::class, 'team_units')
            ->withPivot('position')
            ->withTimestamps();
    }

    public function matchesAsAttacker(): HasMany
    {
        return $this->hasMany(GameMatch::class, 'attacker_team_id');
    }

    public function matchesAsDefender(): HasMany
    {
        return $this->hasMany(GameMatch::class, 'defender_team_id');
    }
}
