<?php

namespace Tests\Feature;

use App\Models\ForgedUnit;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_view_or_evolve_another_users_unit(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $unit = ForgedUnit::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($other)
            ->get(route('units.show', $unit))
            ->assertForbidden();

        $this->actingAs($other)
            ->post(route('units.evolution.evolve', $unit))
            ->assertForbidden();
    }

    public function test_user_cannot_edit_another_users_team(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $team = Team::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($other)
            ->get(route('teams.edit', $team))
            ->assertForbidden();

        $this->actingAs($other)
            ->put(route('teams.update', $team), ['name' => 'Blocked'])
            ->assertForbidden();
    }
}
