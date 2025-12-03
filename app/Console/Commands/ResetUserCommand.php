<?php

namespace App\Console\Commands;

use App\Models\BattleLog;
use App\Models\Blueprint;
use App\Models\BlueprintFragment;
use App\Models\ForgedUnit;
use App\Models\GameMatch;
use App\Models\GearItem;
use App\Models\Material;
use App\Models\Team;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetUserCommand extends Command
{
    protected $signature = 'scanforge:reset-user {userId}';

    protected $description = 'Reset a user state (materials, units, teams, matches) for a fresh loop. Dev-only.';

    public function handle(): int
    {
        $userId = (int) $this->argument('userId');

        DB::transaction(function () use ($userId) {
            $teams = Team::where('user_id', $userId)->pluck('id')->all();

            GameMatch::whereIn('attacker_team_id', $teams)->orWhereIn('defender_team_id', $teams)->each(function (GameMatch $match) {
                BattleLog::where('game_match_id', $match->id)->delete();
                $match->delete();
            });

            BlueprintFragment::where('user_id', $userId)->delete();
            Blueprint::where('user_id', $userId)->delete();
            GearItem::where('user_id', $userId)->delete();
            ForgedUnit::where('user_id', $userId)->delete();
            Material::where('user_id', $userId)->delete();
            Team::where('user_id', $userId)->delete();
        });

        $this->info("User {$userId} state reset. They will need to choose a starter again.");

        return self::SUCCESS;
    }
}
