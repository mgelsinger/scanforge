<?php

namespace App\Services;

use App\Models\Material;
use App\Models\TransmutationRecipe;
use App\Models\User;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;

class TransmutationService
{
    public function __construct(private readonly DatabaseManager $db)
    {
    }

    /**
     * List transmutation recipes with user ownership and availability data.
     */
    public function recipesWithStatus(User $user): Collection
    {
        return TransmutationRecipe::all()->map(function (TransmutationRecipe $recipe) use ($user) {
            $owned = $this->materialQuantity($user, $recipe->input_material_name);
            $outputOwned = $this->materialQuantity($user, $recipe->output_material_name);
            $maxTimes = $recipe->input_quantity > 0 ? intdiv($owned, $recipe->input_quantity) : 0;

            return [
                'recipe' => $recipe,
                'owned' => $owned,
                'output_owned' => $outputOwned,
                'can_transmute' => $maxTimes > 0,
                'max_times' => $maxTimes,
            ];
        });
    }

    public function canTransmute(User $user, TransmutationRecipe $recipe, int $times = 1): array
    {
        $times = max(1, $times);
        if ($recipe->input_quantity <= 0) {
            return ['ok' => false, 'message' => 'Invalid recipe definition.'];
        }

        $owned = $this->materialQuantity($user, $recipe->input_material_name);
        $needed = $recipe->input_quantity * $times;
        $maxTimes = intdiv($owned, $recipe->input_quantity);

        if ($owned < $needed) {
            return [
                'ok' => false,
                'message' => "Not enough {$recipe->input_material_name}.",
                'max_times' => $maxTimes,
            ];
        }

        return ['ok' => true, 'max_times' => $maxTimes, 'times' => $times];
    }

    public function transmute(User $user, TransmutationRecipe $recipe, int $times = 1): array
    {
        $check = $this->canTransmute($user, $recipe, $times);
        if (!($check['ok'] ?? false)) {
            return ['success' => false, 'message' => $check['message'] ?? 'Cannot transmute right now.'];
        }

        $times = min($times, $check['max_times'] ?? $times);
        $inputTotal = $recipe->input_quantity * $times;
        $outputTotal = $recipe->output_quantity * $times;

        return $this->db->transaction(function () use ($user, $recipe, $inputTotal, $outputTotal) {
            /** @var Material|null $input */
            $input = Material::where('user_id', $user->id)
                ->where('name', $recipe->input_material_name)
                ->lockForUpdate()
                ->first();

            if (!$input || $input->quantity < $inputTotal) {
                return ['success' => false, 'message' => 'Not enough materials to transmute.'];
            }

            $input->decrement('quantity', $inputTotal);

            /** @var Material $output */
            $output = Material::firstOrCreate(
                ['user_id' => $user->id, 'name' => $recipe->output_material_name],
                [
                    'category' => 'Transmutation',
                    'material_type' => 'refined',
                    'rarity' => 'rare',
                    'quantity' => 0,
                ]
            );
            $output->increment('quantity', $outputTotal);

            return [
                'success' => true,
                'message' => "Transmutation complete: {$inputTotal} {$recipe->input_material_name} → {$outputTotal} {$recipe->output_material_name}.",
                'output' => $output->fresh(),
            ];
        });
    }

    protected function materialQuantity(User $user, string $name): int
    {
        return (int) (Material::where('user_id', $user->id)->where('name', $name)->value('quantity') ?? 0);
    }
}
