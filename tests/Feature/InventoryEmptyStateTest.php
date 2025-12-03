<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryEmptyStateTest extends TestCase
{
    use RefreshDatabase;

    public function test_inventory_shows_empty_state_messages(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('inventory.index'));

        $response->assertOk();
        $response->assertSee("You don’t have any materials yet", false);
        $response->assertSee("You haven’t discovered any blueprints yet", false);
    }
}
