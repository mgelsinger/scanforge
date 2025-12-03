<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\ScanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScanServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_scan_awards_materials_and_fragments(): void
    {
        $service = app(ScanService::class);
        $user = User::factory()->create();

        $result = $service->scan($user, '1234567890');

        $this->assertArrayHasKey('materials', $result);
        $this->assertArrayHasKey('blueprint', $result);

        $this->assertDatabaseHas('materials', [
            'user_id' => $user->id,
            'name' => $result['materials'][0]['name'],
        ]);

        $this->assertDatabaseHas('blueprint_fragments', [
            'user_id' => $user->id,
        ]);
    }
}
