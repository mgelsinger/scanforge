<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScanValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_invalid_upc_rejected(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/scan', ['upc' => 'abc']);

        $response->assertSessionHasErrors('upc');
    }
}
