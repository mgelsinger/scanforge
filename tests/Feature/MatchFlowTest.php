<?php

namespace Tests\Feature;

use App\Jobs\ResolveMatchJob;
use App\Models\BattleLog;
use App\Models\ForgedUnit;
use App\Models\GameMatch;
use App\Models\Team;
use App\Models\TeamUnit;
use App\Models\User;
use App\Services\BattleSimulatorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MatchFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_match_queue_and_resolution(): void
    {
        $attackerUser = User::factory()->create();
        $defenderUser = User::factory()->create();

        $attackerTeam = Team::factory()->create(['user_id' => $attackerUser->id]);
        $defenderTeam = Team::factory()->create(['user_id' => $defenderUser->id]);

        $attackerUnit = ForgedUnit::factory()->create([
            'user_id' => $attackerUser->id,
            'hp' => 30,
            'attack' => 10,
            'defense' => 5,
            'speed' => 7,
        ]);
        $defenderUnit = ForgedUnit::factory()->create([
            'user_id' => $defenderUser->id,
            'hp' => 15,
            'attack' => 8,
            'defense' => 2,
            'speed' => 5,
        ]);

        TeamUnit::create(['team_id' => $attackerTeam->id, 'forged_unit_id' => $attackerUnit->id, 'position' => 1]);
        TeamUnit::create(['team_id' => $defenderTeam->id, 'forged_unit_id' => $defenderUnit->id, 'position' => 1]);

        $response = $this->actingAs($attackerUser)->post(route('matches.store'), [
            'attacker_team_id' => $attackerTeam->id,
            'defender_team_id' => $defenderTeam->id,
        ]);

        $response->assertRedirect();

        $match = GameMatch::first();
        $job = new ResolveMatchJob($match->id);
        $job->handle(app(BattleSimulatorService::class));

        $match->refresh();
        $this->assertNotNull($match->winner_team_id);
        $this->assertDatabaseHas('battle_logs', ['game_match_id' => $match->id]);
    }
}
