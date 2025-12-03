<?php

namespace Tests\Unit;

use App\Models\Material;
use App\Models\TransmutationRecipe;
use App\Models\User;
use App\Services\TransmutationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransmutationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_transmutation_consumes_and_produces_materials(): void
    {
        $user = User::factory()->create();
        Material::create(['user_id' => $user->id, 'name' => 'Alloy Scraps', 'quantity' => 20]);
        $recipe = TransmutationRecipe::create([
            'label' => 'Purify scraps',
            'input_material_name' => 'Alloy Scraps',
            'input_quantity' => 10,
            'output_material_name' => 'Refined Plate',
            'output_quantity' => 1,
        ]);

        $service = app(TransmutationService::class);
        $result = $service->transmute($user, $recipe, 1);

        $this->assertTrue($result['success']);
        $this->assertEquals(10, Material::where('user_id', $user->id)->where('name', 'Alloy Scraps')->value('quantity'));
        $this->assertDatabaseHas('materials', [
            'user_id' => $user->id,
            'name' => 'Refined Plate',
            'quantity' => 1,
            'material_type' => 'refined',
            'rarity' => 'rare',
        ]);
    }

    public function test_transmutation_fails_when_insufficient_materials(): void
    {
        $user = User::factory()->create();
        Material::create(['user_id' => $user->id, 'name' => 'Alloy Scraps', 'quantity' => 5]);
        $recipe = TransmutationRecipe::create([
            'input_material_name' => 'Alloy Scraps',
            'input_quantity' => 10,
            'output_material_name' => 'Refined Plate',
            'output_quantity' => 1,
        ]);

        $service = app(TransmutationService::class);
        $result = $service->transmute($user, $recipe, 1);

        $this->assertFalse($result['success']);
        $this->assertEquals(5, Material::where('user_id', $user->id)->where('name', 'Alloy Scraps')->value('quantity'));
        $this->assertDatabaseMissing('materials', [
            'user_id' => $user->id,
            'name' => 'Refined Plate',
        ]);
    }
}
