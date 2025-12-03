<?php

namespace Database\Factories;

use App\Models\GameMatch;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GameMatch>
 */
class GameMatchFactory extends Factory
{
    protected $model = GameMatch::class;

    public function definition(): array
    {
        $attacker = Team::factory()->create();
        $defender = Team::factory()->create();

        return [
            'attacker_team_id' => $attacker->id,
            'defender_team_id' => $defender->id,
            'attacker_rating_before' => $attacker->rating ?? 1200,
            'defender_rating_before' => $defender->rating ?? 1200,
        ];
    }
}
