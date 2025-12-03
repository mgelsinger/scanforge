<?php

namespace Tests\Unit;

use App\Models\ForgedUnit;
use App\Models\Team;
use App\Models\TeamUnit;
use App\Models\User;
use App\Services\BattleSimulatorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BattleSimulatorServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_simulation_returns_winner_and_turns(): void
    {
        $service = app(BattleSimulatorService::class);

        $attackerUser = User::factory()->create();
        $defenderUser = User::factory()->create();

        $attackerTeam = Team::factory()->create(['user_id' => $attackerUser->id]);
        $defenderTeam = Team::factory()->create(['user_id' => $defenderUser->id]);

        $fastUnit = ForgedUnit::factory()->create([
            'user_id' => $attackerUser->id,
            'hp' => 30,
            'attack' => 15,
            'defense' => 2,
            'speed' => 10,
        ]);
        $slowUnit = ForgedUnit::factory()->create([
            'user_id' => $defenderUser->id,
            'hp' => 10,
            'attack' => 5,
            'defense' => 1,
            'speed' => 3,
        ]);

        TeamUnit::create([
            'team_id' => $attackerTeam->id,
            'forged_unit_id' => $fastUnit->id,
            'position' => 1,
        ]);

        TeamUnit::create([
            'team_id' => $defenderTeam->id,
            'forged_unit_id' => $slowUnit->id,
            'position' => 1,
        ]);

        $attackerTeam->load('teamUnits.forgedUnit');
        $defenderTeam->load('teamUnits.forgedUnit');

        $result = $service->simulate($attackerTeam, $defenderTeam);

        $this->assertSame($attackerTeam->id, $result['winner_team_id']);
        $this->assertNotEmpty($result['turns']);
        $this->assertEquals('attacker', $result['turns'][0]['actor_team']);
    }
}
