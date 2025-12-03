<?php

namespace Database\Factories;

use App\Models\Blueprint;
use App\Models\ForgedUnit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ForgedUnitFactory extends Factory
{
    protected $model = ForgedUnit::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'blueprint_id' => null,
            'name' => $this->faker->unique()->word() . ' Unit',
            'hp' => 30,
            'attack' => 10,
            'defense' => 5,
            'speed' => 5,
            'rarity' => 'common',
            'trait' => null,
            'metadata' => [],
        ];
    }
}
