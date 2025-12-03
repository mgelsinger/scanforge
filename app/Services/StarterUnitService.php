<?php

namespace App\Services;

class StarterUnitService
{
    /**
        * Return starter archetype definitions.
        */
    public function all(): array
    {
        return [
            'ironling' => [
                'name' => 'Ironling',
                'rarity' => 'common',
                'role' => 'Tanky starter with high HP and DEF.',
                'stats' => ['hp' => 70, 'attack' => 8, 'defense' => 14, 'speed' => 4],
            ],
            'sparket' => [
                'name' => 'Sparket',
                'rarity' => 'common',
                'role' => 'Fast striker with high SPD and solid ATK.',
                'stats' => ['hp' => 45, 'attack' => 12, 'defense' => 6, 'speed' => 12],
            ],
            'mirefiend' => [
                'name' => 'Mirefiend',
                'rarity' => 'common',
                'role' => 'Defensive debuffer archetype.',
                'stats' => ['hp' => 60, 'attack' => 9, 'defense' => 12, 'speed' => 6],
            ],
            'pyrelot' => [
                'name' => 'Pyrelot',
                'rarity' => 'uncommon',
                'role' => 'High ATK, low DEF glass cannon.',
                'stats' => ['hp' => 40, 'attack' => 16, 'defense' => 5, 'speed' => 9],
            ],
            'glintwisp' => [
                'name' => 'Glintwisp',
                'rarity' => 'uncommon',
                'role' => 'Balanced starter.',
                'stats' => ['hp' => 55, 'attack' => 11, 'defense' => 9, 'speed' => 9],
            ],
        ];
    }
}
