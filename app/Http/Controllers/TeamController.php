<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamStoreRequest;
use App\Http\Requests\TeamUpdateRequest;
use App\Models\ForgedUnit;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Team::class);

        $teams = Team::with(['teamUnits.forgedUnit'])
            ->where('user_id', auth()->id())
            ->get();
        $units = ForgedUnit::where('user_id', auth()->id())->get();

        return view('teams.index', ['teams' => $teams, 'units' => $units]);
    }

    public function create(): View
    {
        $this->authorize('create', Team::class);

        $units = ForgedUnit::where('user_id', auth()->id())->get();

        if ($units->isEmpty()) {
            return view('teams.create')->with('units', $units)->with('team_error', 'You need at least one unit to form a team.');
        }

        return view('teams.create', ['units' => $units]);
    }

    public function store(TeamStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Team::class);

        $units = $this->validatedUnits($request->input('units', []));
        if ($units instanceof RedirectResponse) {
            return $units;
        }

        $team = Team::create([
            'user_id' => $request->user()->id,
            'name' => $request->input('name'),
            'is_active' => false,
        ]);

        $this->syncTeamUnits($team, $units);

        return redirect()->route('teams.index')->with('team_success', 'Team created.');
    }

    public function edit(Team $team): View
    {
        $this->authorize('update', $team);

        $units = ForgedUnit::where('user_id', auth()->id())->get();
        $assigned = $team->teamUnits()->pluck('forged_unit_id', 'position')->toArray();

        return view('teams.edit', [
            'team' => $team,
            'units' => $units,
            'assigned' => $assigned,
        ]);
    }

    public function update(TeamUpdateRequest $request, Team $team): RedirectResponse
    {
        $this->authorize('update', $team);

        $units = $this->validatedUnits($request->input('units', []));
        if ($units instanceof RedirectResponse) {
            return $units;
        }

        $team->update([
            'name' => $request->input('name'),
        ]);

        $this->syncTeamUnits($team, $units);

        return redirect()->route('teams.index')->with('team_success', 'Team updated.');
    }

    protected function validatedUnits(array $units)
    {
        $filtered = collect($units)
            ->filter(fn ($u) => !empty($u['id']))
            ->map(fn ($u) => ['id' => (int) $u['id'], 'position' => (int) ($u['position'] ?? 0)])
            ->take(5)
            ->values();

        if ($filtered->isEmpty()) {
            return redirect()->back()->with('team_error', 'Select at least one unit.')->withInput();
        }

        if ($filtered->count() > 5) {
            return redirect()->back()->with('team_error', 'Maximum 5 units per team.')->withInput();
        }

        if ($filtered->pluck('id')->unique()->count() !== $filtered->count()) {
            return redirect()->back()->with('team_error', 'Duplicate units not allowed.')->withInput();
        }

        if ($filtered->pluck('position')->unique()->count() !== $filtered->count()) {
            return redirect()->back()->with('team_error', 'Positions must be unique.')->withInput();
        }

        if ($filtered->contains(fn ($u) => $u['position'] < 1 || $u['position'] > 5)) {
            return redirect()->back()->with('team_error', 'Positions must be between 1 and 5.')->withInput();
        }

        foreach ($filtered as $entry) {
            $unit = ForgedUnit::where('user_id', auth()->id())->find($entry['id']);
            if (!$unit) {
                return redirect()->back()->with('team_error', 'Invalid unit selection.')->withInput();
            }
        }

        return $filtered;
    }

    protected function syncTeamUnits(Team $team, $units): void
    {
        $payload = $units->mapWithKeys(fn ($u) => [
            $u['id'] => ['position' => $u['position']],
        ])->toArray();

        $team->teamUnits()->delete();
        $team->forgedUnits()->sync($payload);
    }
}
