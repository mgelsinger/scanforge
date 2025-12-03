<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BattleLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'turns' => 'array',
        'summary' => 'array',
        'logged_at' => 'datetime',
    ];

    public function gameMatch(): BelongsTo
    {
        return $this->belongsTo(GameMatch::class, 'game_match_id');
    }
}
