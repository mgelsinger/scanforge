<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GameMatch extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'played_at' => 'datetime',
    ];

    public function attackerTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'attacker_team_id');
    }

    public function defenderTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'defender_team_id');
    }

    public function winnerTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'winner_team_id');
    }

    public function winnerUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_user_id');
    }

    public function battleLog(): HasOne
    {
        return $this->hasOne(BattleLog::class);
    }
}
