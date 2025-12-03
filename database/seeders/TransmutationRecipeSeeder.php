<?php

namespace Database\Seeders;

use App\Models\TransmutationRecipe;
use Illuminate\Database\Seeder;

class TransmutationRecipeSeeder extends Seeder
{
    public function run(): void
    {
        $recipes = [
            [
                'label' => 'Purify scraps into plates',
                'input_material_name' => 'Alloy Scraps',
                'input_quantity' => 10,
                'output_material_name' => 'Refined Plate',
                'output_quantity' => 1,
            ],
            [
                'label' => 'Condense circuitry',
                'input_material_name' => 'Circuit Dust',
                'input_quantity' => 8,
                'output_material_name' => 'Nano Core',
                'output_quantity' => 1,
            ],
            [
                'label' => 'Distill bio matter',
                'input_material_name' => 'Bio Gel',
                'input_quantity' => 12,
                'output_material_name' => 'Bio Core',
                'output_quantity' => 1,
            ],
            [
                'label' => 'Focus mystic residue',
                'input_material_name' => 'Ink Essence',
                'input_quantity' => 10,
                'output_material_name' => 'Mystic Shard',
                'output_quantity' => 1,
            ],
        ];

        foreach ($recipes as $data) {
            TransmutationRecipe::updateOrCreate(
                [
                    'input_material_name' => $data['input_material_name'],
                    'output_material_name' => $data['output_material_name'],
                ],
                $data
            );
        }
    }
}
