<?php

namespace Database\Seeders;

use App\Models\Recipe;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        $recipes = [
            [
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
                        'trait' => 'Forager',
                        'stats' => ['hp' => 40, 'attack' => 10, 'defense' => 6, 'speed' => 8],
                    ],
                ],
                'metadata' => [
                    'blueprint_name' => 'Food Core Blueprint',
                ],
            ],
            [
                'name' => 'Alloy Bruiser',
                'station' => 'unit_foundry',
                'inputs' => [
                    ['type' => 'material', 'name' => 'Alloy Scraps', 'quantity' => 4],
                    ['type' => 'material', 'name' => 'Tempered Bolts', 'quantity' => 2],
                ],
                'outputs' => [
                    'unit' => [
                        'name' => 'Alloy Bruiser',
                        'rarity' => 'uncommon',
                        'trait' => 'Bulwark',
                        'stats' => ['hp' => 60, 'attack' => 12, 'defense' => 12, 'speed' => 6],
                    ],
                ],
                'metadata' => [
                    'blueprint_name' => 'Tools Core Blueprint',
                ],
            ],
            [
                'name' => 'Circuit Trinket',
                'station' => 'gear_forge',
                'inputs' => [
                    ['type' => 'material', 'name' => 'Circuit Dust', 'quantity' => 3],
                    ['type' => 'material', 'name' => 'Power Core Shards', 'quantity' => 2],
                ],
                'outputs' => [
                    'gear' => [
                        'name' => 'Circuit Trinket',
                        'type' => 'trinket',
                        'rarity' => 'common',
                        'attributes' => ['attack' => 2, 'speed' => 1],
                    ],
                ],
            ],
            [
                'name' => 'Polymer Mesh Vest',
                'station' => 'gear_forge',
                'inputs' => [
                    ['type' => 'material', 'name' => 'Polymer Beads', 'quantity' => 4],
                    ['type' => 'material', 'name' => 'Flex Mesh', 'quantity' => 2],
                ],
                'outputs' => [
                    'gear' => [
                        'name' => 'Polymer Mesh Vest',
                        'type' => 'armor',
                        'rarity' => 'uncommon',
                        'attributes' => ['defense' => 4, 'hp' => 10],
                    ],
                ],
            ],
            [
                'name' => 'Essence Surge',
                'station' => 'essence_vault',
                'inputs' => [
                    ['type' => 'material', 'name' => 'Bio Gel', 'quantity' => 2],
                    ['type' => 'material', 'name' => 'Med Foam', 'quantity' => 1],
                ],
                'outputs' => [
                    'stat_mods' => ['hp' => 8, 'attack' => 2, 'defense' => 1, 'speed' => 1],
                ],
            ],
        ];

        foreach ($recipes as $data) {
            Recipe::updateOrCreate(
                ['name' => $data['name'], 'station' => $data['station']],
                [
                    'inputs' => $data['inputs'],
                    'outputs' => $data['outputs'],
                    'metadata' => $data['metadata'] ?? [],
                    'required_blueprint_id' => null,
                    'requires_core_item' => false,
                ]
            );
        }
    }
}
