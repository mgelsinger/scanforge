<?php

namespace App\Http\Controllers;

use App\Models\ForgedUnit;
use App\Models\Team;
use App\Models\TeamUnit;
use App\Services\StarterUnitService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StarterController extends Controller
{
    public function __construct(private readonly StarterUnitService $starterUnitService)
    {
        $this->middleware('auth');
    }

    public function show(): View|RedirectResponse
    {
        $user = auth()->user();
        if ($user->hasChosenStarter()) {
            return redirect()->route('dashboard');
        }

        $starters = $this->starterUnitService->all();
        return view('starter.index', ['starters' => $starters]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        if ($user->hasChosenStarter()) {
            return redirect()->route('dashboard');
        }

        $starters = $this->starterUnitService->all();
        $choice = $request->string('starter')->toString();

        if (!array_key_exists($choice, $starters)) {
            return back()->with('starter_error', 'Invalid starter selection.');
        }

        $definition = $starters[$choice];

        DB::transaction(function () use ($user, $definition) {
            /** @var ForgedUnit $unit */
            $unit = ForgedUnit::create([
                'user_id' => $user->id,
                'blueprint_id' => null,
                'name' => $definition['name'],
                'hp' => $definition['stats']['hp'],
                'attack' => $definition['stats']['attack'],
                'defense' => $definition['stats']['defense'],
                'speed' => $definition['stats']['speed'],
                'rarity' => $definition['rarity'],
                'trait' => $definition['role'] ?? null,
                'metadata' => ['starter' => true],
            ]);

            $teamName = $this->defaultTeamName($user->id);
            $team = Team::create([
                'user_id' => $user->id,
                'name' => $teamName,
                'is_active' => true,
            ]);

            TeamUnit::create([
                'team_id' => $team->id,
                'forged_unit_id' => $unit->id,
                'position' => 1,
            ]);

            $user->update(['starter_chosen_at' => now()]);
        });

        return redirect()->route('dashboard')->with('success', 'Starter unit claimed! Welcome to ScanForge.');
    }

    protected function defaultTeamName(int $userId): string
    {
        $base = 'Starter Forge';
        $suffix = '';
        $counter = 1;

        while (Team::where('user_id', $userId)->where('name', $base . $suffix)->exists()) {
            $suffix = ' #' . (++$counter);
        }

        return $base . $suffix;
    }
}
