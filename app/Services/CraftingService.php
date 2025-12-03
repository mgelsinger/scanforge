<?php

namespace App\Services;

use App\Models\Blueprint;
use App\Models\ForgedUnit;
use App\Models\GearItem;
use App\Models\Material;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\DatabaseManager;

class CraftingService
{
    public function __construct(private readonly DatabaseManager $db)
    {
    }

    public function craftUnit(User $user, Recipe $recipe): array
    {
        if ($recipe->station !== 'unit_foundry') {
            return $this->failure('Invalid station for unit crafting.');
        }

        $blueprintName = data_get($recipe->metadata, 'blueprint_name');
        $blueprint = $blueprintName
            ? Blueprint::where('user_id', $user->id)->where('name', $blueprintName)->first()
            : null;

        if (!$blueprint || !$blueprint->is_completed) {
            return $this->failure('Required blueprint is incomplete or missing.');
        }

        $materialInputs = $this->materialInputs($recipe);
        $materialCheck = $this->ensureMaterials($user, $materialInputs);
        if (!$materialCheck['ok']) {
            return $this->failure($materialCheck['message']);
        }

        return $this->db->transaction(function () use ($user, $recipe, $materialInputs, $blueprint) {
            $this->consumeMaterials($user, $materialInputs);

            $unitData = $this->unitOutput($recipe);
            /** @var ForgedUnit $unit */
            $unit = ForgedUnit::create([
                'user_id' => $user->id,
                'blueprint_id' => $blueprint?->id,
                'name' => $unitData['name'],
                'hp' => $unitData['stats']['hp'],
                'attack' => $unitData['stats']['attack'],
                'defense' => $unitData['stats']['defense'],
                'speed' => $unitData['stats']['speed'],
                'rarity' => $unitData['rarity'] ?? 'common',
                'trait' => $unitData['trait'] ?? null,
                'metadata' => $unitData['metadata'] ?? null,
            ]);

            return $this->success('Unit crafted successfully.', ['unit' => $unit]);
        });
    }

    public function craftGear(User $user, Recipe $recipe): array
    {
        if ($recipe->station !== 'gear_forge') {
            return $this->failure('Invalid station for gear crafting.');
        }

        $materialInputs = $this->materialInputs($recipe);
        $materialCheck = $this->ensureMaterials($user, $materialInputs);
        if (!$materialCheck['ok']) {
            return $this->failure($materialCheck['message']);
        }

        return $this->db->transaction(function () use ($user, $recipe, $materialInputs) {
            $this->consumeMaterials($user, $materialInputs);

            $gearData = $this->gearOutput($recipe);
            /** @var GearItem $gear */
            $gear = GearItem::create([
                'user_id' => $user->id,
                'blueprint_id' => null,
                'name' => $gearData['name'],
                'type' => $gearData['type'] ?? null,
                'rarity' => $gearData['rarity'] ?? 'common',
                'attributes' => $gearData['attributes'] ?? [],
            ]);

            return $this->success('Gear crafted successfully.', ['gear' => $gear]);
        });
    }

    public function upgradeUnit(User $user, Recipe $recipe, int $unitId): array
    {
        if ($recipe->station !== 'essence_vault') {
            return $this->failure('Invalid station for upgrading.');
        }

        $unit = ForgedUnit::where('user_id', $user->id)->find($unitId);
        if (!$unit) {
            return $this->failure('Unit not found for upgrade.');
        }

        $materialInputs = $this->materialInputs($recipe);
        $materialCheck = $this->ensureMaterials($user, $materialInputs);
        if (!$materialCheck['ok']) {
            return $this->failure($materialCheck['message']);
        }

        $statMods = data_get($recipe->outputs, 'stat_mods', []);

        return $this->db->transaction(function () use ($user, $materialInputs, $unit, $statMods) {
            $this->consumeMaterials($user, $materialInputs);

            $unit->hp += (int) ($statMods['hp'] ?? 0);
            $unit->attack += (int) ($statMods['attack'] ?? 0);
            $unit->defense += (int) ($statMods['defense'] ?? 0);
            $unit->speed += (int) ($statMods['speed'] ?? 0);
            $unit->save();

            return $this->success('Unit upgraded successfully.', ['unit' => $unit]);
        });
    }

    protected function materialInputs(Recipe $recipe): array
    {
        return collect($recipe->inputs ?? [])
            ->where('type', 'material')
            ->map(fn ($input) => [
                'name' => $input['name'],
                'quantity' => (int) $input['quantity'],
            ])->values()->all();
    }

    protected function unitOutput(Recipe $recipe): array
    {
        return [
            'name' => data_get($recipe->outputs, 'unit.name', 'Forged Unit'),
            'rarity' => data_get($recipe->outputs, 'unit.rarity', 'common'),
            'trait' => data_get($recipe->outputs, 'unit.trait'),
            'metadata' => data_get($recipe->outputs, 'unit.metadata'),
            'stats' => [
                'hp' => (int) data_get($recipe->outputs, 'unit.stats.hp', 10),
                'attack' => (int) data_get($recipe->outputs, 'unit.stats.attack', 5),
                'defense' => (int) data_get($recipe->outputs, 'unit.stats.defense', 5),
                'speed' => (int) data_get($recipe->outputs, 'unit.stats.speed', 5),
            ],
        ];
    }

    protected function gearOutput(Recipe $recipe): array
    {
        return [
            'name' => data_get($recipe->outputs, 'gear.name', 'Crafted Gear'),
            'type' => data_get($recipe->outputs, 'gear.type'),
            'rarity' => data_get($recipe->outputs, 'gear.rarity', 'common'),
            'attributes' => data_get($recipe->outputs, 'gear.attributes', []),
        ];
    }

    protected function ensureMaterials(User $user, array $materials): array
    {
        foreach ($materials as $material) {
            $record = Material::where('user_id', $user->id)
                ->where('name', $material['name'])
                ->first();

            if (!$record || $record->quantity < $material['quantity']) {
                return [
                    'ok' => false,
                    'message' => "Not enough {$material['name']} (need {$material['quantity']}).",
                ];
            }
        }

        return ['ok' => true];
    }

    protected function consumeMaterials(User $user, array $materials): void
    {
        foreach ($materials as $material) {
            Material::where('user_id', $user->id)
                ->where('name', $material['name'])
                ->decrement('quantity', $material['quantity']);
        }
    }

    protected function success(string $message, array $data = []): array
    {
        return array_merge(['success' => true, 'message' => $message], $data);
    }

    protected function failure(string $message): array
    {
        return ['success' => false, 'message' => $message];
    }
}
