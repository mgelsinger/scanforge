<?php

namespace App\Jobs;

use App\Models\BattleLog;
use App\Models\GameMatch;
use App\Services\BattleSimulatorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ResolveMatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $matchId)
    {
    }

    public function handle(BattleSimulatorService $simulator): void
    {
        $match = GameMatch::with([
            'attackerTeam.user',
            'attackerTeam.teamUnits.forgedUnit',
            'defenderTeam.user',
            'defenderTeam.teamUnits.forgedUnit',
        ])->find($this->matchId);

        if (!$match) {
            return;
        }

        $attacker = $match->attackerTeam;
        $defender = $match->defenderTeam;

        $result = $simulator->simulate($attacker, $defender);

        $winnerTeamId = $result['winner_team_id'];
        $winnerUserId = $winnerTeamId === $attacker->id ? $attacker->user_id : $defender->user_id;

        $teamRatings = $this->applyRating($attacker->rating, $defender->rating, $winnerTeamId === $attacker->id);
        $userRatings = $this->applyRating($attacker->user->rating ?? 1200, $defender->user->rating ?? 1200, $winnerTeamId === $attacker->id);

        DB::transaction(function () use ($match, $winnerTeamId, $winnerUserId, $teamRatings, $userRatings, $result) {
            $match->update([
                'winner_team_id' => $winnerTeamId,
                'winner_user_id' => $winnerUserId,
                'rating_change' => $teamRatings['delta'],
                'attacker_rating_before' => $teamRatings['attacker_before'],
                'defender_rating_before' => $teamRatings['defender_before'],
                'attacker_rating_after' => $teamRatings['attacker_after'],
                'defender_rating_after' => $teamRatings['defender_after'],
                'played_at' => now(),
            ]);

            $match->attackerTeam->update(['rating' => $teamRatings['attacker_after']]);
            $match->defenderTeam->update(['rating' => $teamRatings['defender_after']]);

            optional($match->attackerTeam->user)->update(['rating' => $userRatings['attacker_after']]);
            optional($match->defenderTeam->user)->update(['rating' => $userRatings['defender_after']]);

            BattleLog::create([
                'game_match_id' => $match->id,
                'turns' => $result['turns'],
                'summary' => $result['summary'],
                'logged_at' => now(),
            ]);
        });
    }

    protected function applyRating(int $attackerRating, int $defenderRating, bool $attackerWon): array
    {
        $k = 32;
        $expectedAttacker = 1 / (1 + 10 ** (($defenderRating - $attackerRating) / 400));
        $expectedDefender = 1 / (1 + 10 ** (($attackerRating - $defenderRating) / 400));

        $attackerScore = $attackerWon ? 1 : 0;
        $defenderScore = $attackerWon ? 0 : 1;

        $attackerAfter = (int) round($attackerRating + $k * ($attackerScore - $expectedAttacker));
        $defenderAfter = (int) round($defenderRating + $k * ($defenderScore - $expectedDefender));

        return [
            'delta' => abs($attackerAfter - $attackerRating),
            'attacker_before' => $attackerRating,
            'defender_before' => $defenderRating,
            'attacker_after' => max(0, $attackerAfter),
            'defender_after' => max(0, $defenderAfter),
        ];
    }
}
