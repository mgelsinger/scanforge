<?php

namespace Database\Factories;

use App\Models\Material;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Material>
 */
class MaterialFactory extends Factory
{
    protected $model = Material::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->randomElement(['Alloy Scraps', 'Nutrient Shards', 'Circuit Dust', 'Bio Gel']),
            'category' => $this->faker->randomElement(['Tools', 'Food', 'Electronics', 'Health']),
            'material_type' => 'common',
            'rarity' => 'common',
            'quantity' => $this->faker->numberBetween(1, 20),
        ];
    }
}
