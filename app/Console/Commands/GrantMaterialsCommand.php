<?php

namespace App\Console\Commands;

use App\Models\Material;
use App\Models\User;
use Illuminate\Console\Command;

class GrantMaterialsCommand extends Command
{
    protected $signature = 'scanforge:grant-materials {userId} {materialName} {amount}';

    protected $description = 'Grant a user a quantity of a material (dev utility).';

    public function handle(): int
    {
        $userId = (int) $this->argument('userId');
        $materialName = (string) $this->argument('materialName');
        $amount = max(0, (int) $this->argument('amount'));

        $user = User::find($userId);
        if (!$user) {
            $this->error('User not found.');
            return self::FAILURE;
        }

        /** @var Material $material */
        $material = Material::firstOrCreate(
            ['user_id' => $userId, 'name' => $materialName],
            [
                'category' => 'Granted',
                'material_type' => 'common',
                'rarity' => 'common',
                'quantity' => 0,
            ]
        );

        $material->increment('quantity', $amount);

        $this->info("Granted {$amount} {$materialName} to user {$user->email}.");

        return self::SUCCESS;
    }
}
