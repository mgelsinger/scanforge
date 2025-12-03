<?php

namespace Tests\Feature;

use App\Models\Material;
use App\Models\TransmutationRecipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransmutationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_transmuter_page_shows_recipes(): void
    {
        $user = User::factory()->create();
        $recipe = TransmutationRecipe::create([
            'label' => 'Condense circuitry',
            'input_material_name' => 'Circuit Dust',
            'input_quantity' => 8,
            'output_material_name' => 'Nano Core',
            'output_quantity' => 1,
        ]);

        $response = $this->actingAs($user)->get(route('craft.transmuter'));

        $response->assertOk();
        $response->assertSee($recipe->label);
        $response->assertSee($recipe->input_material_name);
        $response->assertSee($recipe->output_material_name);
    }

    public function test_transmutation_succeeds_with_enough_materials(): void
    {
        $user = User::factory()->create();
        Material::create(['user_id' => $user->id, 'name' => 'Circuit Dust', 'quantity' => 16]);
        $recipe = TransmutationRecipe::create([
            'input_material_name' => 'Circuit Dust',
            'input_quantity' => 8,
            'output_material_name' => 'Nano Core',
            'output_quantity' => 1,
        ]);

        $response = $this->actingAs($user)->post(route('craft.transmuter.transmute', $recipe), [
            'times' => 2,
        ]);

        $response->assertRedirect(route('craft.transmuter'));
        $response->assertSessionHas('craft_success');

        $this->assertDatabaseHas('materials', [
            'user_id' => $user->id,
            'name' => 'Circuit Dust',
            'quantity' => 0,
        ]);
        $this->assertDatabaseHas('materials', [
            'user_id' => $user->id,
            'name' => 'Nano Core',
            'quantity' => 2,
        ]);
    }

    public function test_transmutation_fails_with_insufficient_materials(): void
    {
        $user = User::factory()->create();
        Material::create(['user_id' => $user->id, 'name' => 'Circuit Dust', 'quantity' => 4]);
        $recipe = TransmutationRecipe::create([
            'input_material_name' => 'Circuit Dust',
            'input_quantity' => 8,
            'output_material_name' => 'Nano Core',
            'output_quantity' => 1,
        ]);

        $response = $this->actingAs($user)->post(route('craft.transmuter.transmute', $recipe), [
            'times' => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('craft_error');
        $this->assertDatabaseHas('materials', [
            'user_id' => $user->id,
            'name' => 'Circuit Dust',
            'quantity' => 4,
        ]);
        $this->assertDatabaseMissing('materials', [
            'user_id' => $user->id,
            'name' => 'Nano Core',
        ]);
    }
}
