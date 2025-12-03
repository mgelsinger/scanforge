<?php

namespace App\Services;

use App\Models\Team;

class BattleSimulatorService
{
    public function simulate(Team $attacker, Team $defender): array
    {
        $state = [
            'attacker' => $this->buildRoster($attacker),
            'defender' => $this->buildRoster($defender),
        ];

        $turns = [];
        $turnNumber = 1;

        while ($this->hasLiving($state['attacker']) && $this->hasLiving($state['defender'])) {
            $acting = $this->nextActor($state);
            if (!$acting) {
                break;
            }

            $targetTeamKey = $acting['team'] === 'attacker' ? 'defender' : 'attacker';
            $target = $this->nextTarget($state[$targetTeamKey]);

            if (!$target) {
                break;
            }

            $damage = max(1, $acting['attack'] - $target['defense']);
            $state[$targetTeamKey][$target['index']]['hp'] -= $damage;

            $turns[] = [
                'turn' => $turnNumber++,
                'actor_team' => $acting['team'],
                'actor_name' => $acting['name'],
                'actor_position' => $acting['position'],
                'target_team' => $targetTeamKey,
                'target_name' => $target['name'],
                'target_position' => $target['position'],
                'damage' => $damage,
                'target_remaining_hp' => max(0, $state[$targetTeamKey][$target['index']]['hp']),
                'target_defeated' => $state[$targetTeamKey][$target['index']]['hp'] <= 0,
            ];
        }

        $winner = $this->hasLiving($state['attacker']) ? $attacker : $defender;
        $winnerKey = $this->hasLiving($state['attacker']) ? 'attacker' : 'defender';

        return [
            'winner_team_id' => $winner->id,
            'winner_key' => $winnerKey,
            'turns' => $turns,
            'summary' => [
                'attacker_team_id' => $attacker->id,
                'defender_team_id' => $defender->id,
                'winner_team_id' => $winner->id,
                'rounds' => count($turns),
            ],
        ];
    }

    protected function buildRoster(Team $team): array
    {
        return $team->teamUnits
            ->sortBy('position')
            ->values()
            ->map(function ($teamUnit, $index) {
                return [
                    'index' => $index,
                    'team_unit_id' => $teamUnit->id,
                    'position' => $teamUnit->position,
                    'name' => $teamUnit->forgedUnit->name,
                    'hp' => $teamUnit->forgedUnit->hp,
                    'attack' => $teamUnit->forgedUnit->attack,
                    'defense' => $teamUnit->forgedUnit->defense,
                    'speed' => $teamUnit->forgedUnit->speed,
                ];
            })->all();
    }

    protected function hasLiving(array $roster): bool
    {
        return collect($roster)->some(fn ($u) => $u['hp'] > 0);
    }

    protected function nextActor(array $state): ?array
    {
        $living = collect($state['attacker'])
            ->filter(fn ($u) => $u['hp'] > 0)
            ->map(fn ($u) => array_merge($u, ['team' => 'attacker']))
            ->merge(
                collect($state['defender'])
                    ->filter(fn ($u) => $u['hp'] > 0)
                    ->map(fn ($u) => array_merge($u, ['team' => 'defender']))
            )
            ->values();

        if ($living->isEmpty()) {
            return null;
        }

        return $living
            ->sortBy([
                fn ($a, $b) => $a['speed'] === $b['speed'] ? 0 : ($a['speed'] < $b['speed'] ? 1 : -1),
                fn ($a, $b) => $a['position'] <=> $b['position'],
            ])
            ->first();
    }

    protected function nextTarget(array $roster): ?array
    {
        return collect($roster)
            ->filter(fn ($u) => $u['hp'] > 0)
            ->sortBy('position')
            ->first();
    }
}
