<?php

namespace Tests\Feature;

use App\Models\Blueprint;
use App\Models\Material;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CraftingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_unit_crafting_flow(): void
    {
        $user = User::factory()->create();

        $blueprint = Blueprint::create([
            'user_id' => $user->id,
            'name' => 'Food Core Blueprint',
            'required_fragments' => 1,
            'fragments_collected' => 1,
            'is_completed' => true,
        ]);

        Material::create(['user_id' => $user->id, 'name' => 'Nutrient Shards', 'quantity' => 5]);
        Material::create(['user_id' => $user->id, 'name' => 'Foraged Herbs', 'quantity' => 5]);

        $recipe = Recipe::create([
            'name' => 'Foraged Scout',
            'station' => 'unit_foundry',
            'inputs' => [
                ['type' => 'material', 'name' => 'Nutrient Shards', 'quantity' => 3],
                ['type' => 'material', 'name' => 'Foraged Herbs', 'quantity' => 2],
            ],
            'outputs' => [
                'unit' => [
                    'name' => 'Foraged Scout',
                    'rarity' => 'common',
                    'stats' => ['hp' => 40, 'attack' => 10, 'defense' => 6, 'speed' => 8],
                ],
            ],
            'metadata' => [
                'blueprint_name' => $blueprint->name,
            ],
        ]);

        $response = $this->actingAs($user)->post(route('craft.unit.craft'), [
            'recipe_id' => $recipe->id,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('forged_units', [
            'user_id' => $user->id,
            'name' => 'Foraged Scout',
        ]);
    }
}
