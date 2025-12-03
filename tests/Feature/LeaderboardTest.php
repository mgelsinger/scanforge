<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaderboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_leaderboard_shows_top_users(): void
    {
        User::factory()->create(['name' => 'High', 'rating' => 1500]);
        User::factory()->create(['name' => 'Low', 'rating' => 1100]);

        $user = User::factory()->create(['rating' => 1200]);

        $response = $this->actingAs($user)->get(route('leaderboard.index'));

        $response->assertOk();
        $response->assertSee('High');
        $response->assertSee('Low');
    }
}
