<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScanFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_scan_flow_stores_rewards(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/scan', [
            'upc' => '999123456',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('materials', ['user_id' => $user->id]);
        $this->assertDatabaseHas('blueprint_fragments', ['user_id' => $user->id]);
    }
}
