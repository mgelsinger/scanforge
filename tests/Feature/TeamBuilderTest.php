<?php

namespace Tests\Feature;

use App\Models\ForgedUnit;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_team_with_positions(): void
    {
        $user = User::factory()->create();
        $unitA = ForgedUnit::factory()->create(['user_id' => $user->id]);
        $unitB = ForgedUnit::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(route('teams.store'), [
            'name' => 'Alpha',
            'units' => [
                ['id' => $unitA->id, 'position' => 1],
                ['id' => $unitB->id, 'position' => 2],
            ],
        ]);

        $response->assertRedirect(route('teams.index'));
        $this->assertDatabaseHas('teams', ['name' => 'Alpha', 'user_id' => $user->id]);
        $team = Team::where('name', 'Alpha')->first();
        $this->assertDatabaseHas('team_units', ['team_id' => $team->id, 'forged_unit_id' => $unitA->id, 'position' => 1]);
        $this->assertDatabaseHas('team_units', ['team_id' => $team->id, 'forged_unit_id' => $unitB->id, 'position' => 2]);
    }
}
