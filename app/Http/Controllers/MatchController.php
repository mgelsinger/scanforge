<?php

namespace App\Http\Controllers;

use App\Jobs\ResolveMatchJob;
use App\Models\GameMatch;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(): View
    {
        $myTeams = Team::where('user_id', auth()->id())->get();
        $opponents = Team::with('user')->where('user_id', '!=', auth()->id())->get();

        return view('matches.create', [
            'myTeams' => $myTeams,
            'opponents' => $opponents,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'attacker_team_id' => ['required', 'integer', 'exists:teams,id'],
            'defender_team_id' => ['required', 'integer', 'exists:teams,id', 'different:attacker_team_id'],
        ]);

        $attackerTeam = Team::with('teamUnits.forgedUnit')->where('user_id', $request->user()->id)->find($request->integer('attacker_team_id'));
        if (!$attackerTeam) {
            return back()->with('match_error', 'You can only queue matches with your own team.')->withInput();
        }

        $defenderTeam = Team::with('teamUnits.forgedUnit')->findOrFail($request->integer('defender_team_id'));

        if ($attackerTeam->teamUnits()->count() === 0 || $defenderTeam->teamUnits()->count() === 0) {
            return back()->with('match_error', 'Both teams must have at least one unit.')->withInput();
        }

        $match = GameMatch::create([
            'attacker_team_id' => $attackerTeam->id,
            'defender_team_id' => $defenderTeam->id,
            'attacker_rating_before' => $attackerTeam->rating,
            'defender_rating_before' => $defenderTeam->rating,
        ]);

        ResolveMatchJob::dispatch($match->id);

        return redirect()->route('matches.show', $match)->with('match_success', 'Match queued for resolution.');
    }

    public function show(GameMatch $match): View
    {
        $this->authorizeMatchAccess($match);

        $match->load(['attackerTeam', 'defenderTeam', 'winnerTeam', 'battleLog']);

        return view('matches.show', ['match' => $match]);
    }

    public function log(GameMatch $match): View
    {
        $this->authorizeMatchAccess($match);

        $match->load(['battleLog', 'attackerTeam', 'defenderTeam']);

        return view('matches.log', ['match' => $match, 'log' => $match->battleLog]);
    }

    protected function authorizeMatchAccess(GameMatch $match): void
    {
        $userId = auth()->id();
        $teamIds = [$match->attacker_team_id, $match->defender_team_id];
        $ownsTeam = Team::whereIn('id', $teamIds)->where('user_id', $userId)->exists();
        abort_unless($ownsTeam, 403);
    }
}
