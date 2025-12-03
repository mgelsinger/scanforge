<?php

namespace Database\Seeders;

use App\Models\Evolution;
use Illuminate\Database\Seeder;

class EvolutionSeeder extends Seeder
{
    public function run(): void
    {
        Evolution::updateOrCreate(
            ['from_tier' => 1, 'to_tier' => 2],
            [
                'required_materials' => [
                    'Alloy Scraps' => 10,
                    'Nutrient Shards' => 8,
                    'Foraged Herbs' => 6,
                ],
                'required_wins' => 0,
                'stat_modifiers' => [
                    'hp' => 20,
                    'attack' => 5,
                    'defense' => 4,
                    'speed' => 2,
                ],
                'new_name' => null,
                'passive_trait' => 'resolute',
            ]
        );
    }
}
