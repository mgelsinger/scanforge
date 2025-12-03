<?php

namespace App\Services;

use App\Models\Blueprint;
use App\Models\BlueprintFragment;
use App\Models\Material;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ScanService
{
    /**
     * Process a UPC scan for a user and return reward details.
     */
    public function scan(User $user, string $upc): array
    {
        $category = $this->categorize($upc);

        return DB::transaction(function () use ($user, $upc, $category) {
            $materialDrops = $this->generateMaterialDrops($category);
            $materials = $this->persistMaterials($user, $materialDrops, $category);

            $blueprintReward = $this->generateBlueprintFragments($user, $category);

            return [
                'upc' => $upc,
                'category' => $category,
                'materials' => $materials,
                'blueprint' => $blueprintReward,
            ];
        });
    }

    /**
     * Simple deterministic categorization based on UPC hash.
     */
    protected function categorize(string $upc): string
    {
        $categories = ['Food', 'Tools', 'Electronics', 'Books', 'Health', 'Toys'];
        $index = abs(crc32($upc)) % count($categories);

        return $categories[$index];
    }

    /**
     * Define material rewards per category with small random stacks.
     */
    protected function generateMaterialDrops(string $category): array
    {
        $definitions = [
            'Food' => ['Nutrient Shards', 'Foraged Herbs'],
            'Tools' => ['Alloy Scraps', 'Tempered Bolts'],
            'Electronics' => ['Circuit Dust', 'Power Core Shards'],
            'Books' => ['Knowledge Scrap', 'Ink Essence'],
            'Health' => ['Bio Gel', 'Med Foam'],
            'Toys' => ['Polymer Beads', 'Flex Mesh'],
        ];

        $materials = $definitions[$category] ?? ['Raw Matter'];

        return collect($materials)->map(function ($name) {
            return [
                'name' => $name,
                'quantity' => random_int(1, 3),
            ];
        })->all();
    }

    /**
     * Persist material drops and return updated totals.
     */
    protected function persistMaterials(User $user, array $drops, string $category): array
    {
        $results = [];

        foreach ($drops as $drop) {
            /** @var Material $material */
            $material = Material::firstOrCreate(
                ['user_id' => $user->id, 'name' => $drop['name']],
                ['category' => $category, 'quantity' => 0]
            );

            $material->increment('quantity', $drop['quantity']);

            $results[] = [
                'name' => $material->name,
                'awarded' => $drop['quantity'],
                'total' => $material->quantity,
            ];
        }

        return $results;
    }

    /**
     * Blueprint fragment drop logic.
     */
    protected function generateBlueprintFragments(User $user, string $category): array
    {
        $blueprintName = "{$category} Core Blueprint";
        $requiredFragments = 10;

        /** @var Blueprint $blueprint */
        $blueprint = Blueprint::firstOrCreate(
            ['user_id' => $user->id, 'name' => $blueprintName],
            [
                'category' => $category,
                'required_fragments' => $requiredFragments,
                'fragments_collected' => 0,
                'is_completed' => false,
            ]
        );

        // 60% chance to drop 1-3 fragments
        $dropsAwarded = random_int(1, 100) <= 60 ? random_int(1, 3) : 0;

        /** @var BlueprintFragment $fragment */
        $fragment = BlueprintFragment::firstOrCreate(
            ['blueprint_id' => $blueprint->id, 'user_id' => $user->id],
            ['quantity' => 0]
        );

        if ($dropsAwarded > 0) {
            $fragment->increment('quantity', $dropsAwarded);
        }

        $blueprint->fragments_collected = $fragment->quantity;
        $blueprint->is_completed = $blueprint->fragments_collected >= $blueprint->required_fragments;
        $blueprint->save();

        return [
            'name' => $blueprintName,
            'awarded' => $dropsAwarded,
            'total_fragments' => $fragment->quantity,
            'required_fragments' => $blueprint->required_fragments,
            'completed' => $blueprint->is_completed,
        ];
    }
}
