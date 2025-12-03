<?php

namespace Tests\Unit;

use App\Models\Evolution;
use App\Models\ForgedUnit;
use App\Models\Material;
use App\Models\User;
use App\Services\EvolutionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EvolutionServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_evolve_and_consumes_materials(): void
    {
        $user = User::factory()->create();
        $unit = ForgedUnit::factory()->create([
            'user_id' => $user->id,
            'hp' => 50,
            'attack' => 10,
            'defense' => 8,
            'speed' => 6,
            'tier' => 1,
        ]);

        Evolution::create([
            'from_tier' => 1,
            'to_tier' => 2,
            'required_materials' => ['Alloy Scraps' => 5],
            'stat_modifiers' => ['hp' => 10, 'attack' => 2, 'defense' => 1, 'speed' => 1],
            'new_name' => 'Evolved Unit',
            'passive_trait' => 'resolute',
        ]);

        Material::create([
            'user_id' => $user->id,
            'name' => 'Alloy Scraps',
            'quantity' => 6,
        ]);

        $service = app(EvolutionService::class);
        $check = $service->canEvolve($user, $unit);
        $this->assertTrue($check['ok']);

        $result = $service->evolve($user, $unit);
        $this->assertTrue($result['success']);

        $unit->refresh();
        $this->assertEquals(2, $unit->tier);
        $this->assertEquals(60, $unit->hp);
        $this->assertEquals('Evolved Unit', $unit->variant_name);
        $this->assertEquals('resolute', $unit->passive_trait);

        $this->assertDatabaseHas('materials', [
            'user_id' => $user->id,
            'name' => 'Alloy Scraps',
            'quantity' => 1,
        ]);
    }

    public function test_cannot_evolve_without_materials(): void
    {
        $user = User::factory()->create();
        $unit = ForgedUnit::factory()->create([
            'user_id' => $user->id,
            'tier' => 1,
        ]);

        Evolution::create([
            'from_tier' => 1,
            'to_tier' => 2,
            'required_materials' => ['Alloy Scraps' => 5],
        ]);

        $service = app(EvolutionService::class);
        $result = $service->evolve($user, $unit);

        $this->assertFalse($result['success']);
        $unit->refresh();
        $this->assertEquals(1, $unit->tier);
    }
}
