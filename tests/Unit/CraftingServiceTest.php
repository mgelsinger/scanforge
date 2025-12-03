<?php

namespace Tests\Unit;

use App\Models\Blueprint;
use App\Models\ForgedUnit;
use App\Models\Material;
use App\Models\Recipe;
use App\Models\User;
use App\Services\CraftingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CraftingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_craft_unit_consumes_materials_and_creates_unit(): void
    {
        $service = app(CraftingService::class);
        $user = User::factory()->create();

        $blueprint = Blueprint::create([
            'user_id' => $user->id,
            'name' => 'Test Blueprint',
            'required_fragments' => 1,
            'fragments_collected' => 1,
            'is_completed' => true,
        ]);

        Material::create(['user_id' => $user->id, 'name' => 'Alloy Scraps', 'quantity' => 5]);
        Material::create(['user_id' => $user->id, 'name' => 'Tempered Bolts', 'quantity' => 3]);

        $recipe = Recipe::create([
            'name' => 'Test Unit',
            'station' => 'unit_foundry',
            'inputs' => [
                ['type' => 'material', 'name' => 'Alloy Scraps', 'quantity' => 3],
                ['type' => 'material', 'name' => 'Tempered Bolts', 'quantity' => 2],
            ],
            'outputs' => [
                'unit' => [
                    'name' => 'Unit X',
                    'rarity' => 'common',
                    'stats' => ['hp' => 20, 'attack' => 10, 'defense' => 5, 'speed' => 5],
                ],
            ],
            'metadata' => [
                'blueprint_name' => $blueprint->name,
            ],
            'requires_core_item' => false,
        ]);

        $result = $service->craftUnit($user, $recipe);

        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('forged_units', ['name' => 'Unit X', 'user_id' => $user->id]);
        $this->assertEquals(2, Material::where('user_id', $user->id)->where('name', 'Alloy Scraps')->first()->quantity);
        $this->assertEquals(1, Material::where('user_id', $user->id)->where('name', 'Tempered Bolts')->first()->quantity);
    }
}
