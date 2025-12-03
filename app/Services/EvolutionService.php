<?php

namespace App\Services;

use App\Models\Evolution;
use App\Models\ForgedUnit;
use App\Models\Material;
use App\Models\User;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\DB;

class EvolutionService
{
    public function __construct(private readonly DatabaseManager $db)
    {
    }

    public function getNextEvolution(ForgedUnit $unit): ?Evolution
    {
        return Evolution::where('from_tier', $unit->tier ?? 1)
            ->where('to_tier', ($unit->tier ?? 1) + 1)
            ->first();
    }

    public function canEvolve(User $user, ForgedUnit $unit): array
    {
        $evolution = $this->getNextEvolution($unit);
        if (!$evolution) {
            return ['ok' => false, 'reason' => 'This unit has reached its maximum tier.'];
        }

        $materials = $evolution->required_materials ?? [];
        foreach ($materials as $name => $quantity) {
            $have = Material::where('user_id', $user->id)->where('name', $name)->value('quantity') ?? 0;
            if ($have < $quantity) {
                return ['ok' => false, 'reason' => "Not enough $name (need $quantity)."];
            }
        }

        if ($evolution->required_wins > 0) {
            // placeholder: wins tracking not implemented; assume zero
            return ['ok' => false, 'reason' => 'Win requirement not met.'];
        }

        return ['ok' => true, 'reason' => null, 'evolution' => $evolution];
    }

    public function evolve(User $user, ForgedUnit $unit): array
    {
        $check = $this->canEvolve($user, $unit);
        if (!($check['ok'] ?? false)) {
            return ['success' => false, 'message' => $check['reason'] ?? 'Cannot evolve'];
        }

        /** @var Evolution $evolution */
        $evolution = $check['evolution'];

        return $this->db->transaction(function () use ($user, $unit, $evolution) {
            foreach ($evolution->required_materials ?? [] as $name => $quantity) {
                Material::where('user_id', $user->id)
                    ->where('name', $name)
                    ->decrement('quantity', $quantity);
            }

            $unit->tier = $evolution->to_tier;
            $mods = $evolution->stat_modifiers ?? [];
            $unit->hp += (int) ($mods['hp'] ?? 0);
            $unit->attack += (int) ($mods['attack'] ?? 0);
            $unit->defense += (int) ($mods['defense'] ?? 0);
            $unit->speed += (int) ($mods['speed'] ?? 0);
            if ($evolution->new_name) {
                $unit->variant_name = $evolution->new_name;
            }
            if ($evolution->passive_trait) {
                $unit->passive_trait = $evolution->passive_trait;
            }
            $unit->save();

            return ['success' => true, 'unit' => $unit];
        });
    }
}
