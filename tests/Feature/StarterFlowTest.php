<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StarterFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_redirects_to_starter_when_not_chosen(): void
    {
        $user = User::factory()->create(['starter_chosen_at' => null]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect(route('starter.show'));
    }

    public function test_choosing_starter_creates_unit_and_team(): void
    {
        $user = User::factory()->create(['starter_chosen_at' => null]);

        $response = $this->actingAs($user)->post(route('starter.store'), [
            'starter' => 'ironling',
        ]);

        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('forged_units', ['user_id' => $user->id, 'name' => 'Ironling']);
        $this->assertDatabaseHas('teams', ['user_id' => $user->id]);
        $this->assertDatabaseHas('team_units', ['position' => 1]);
        $user->refresh();
        $this->assertTrue($user->hasChosenStarter());
    }
}
