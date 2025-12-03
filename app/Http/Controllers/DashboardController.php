<?php

namespace App\Http\Controllers;

use App\Models\Blueprint;
use App\Models\ForgedUnit;
use App\Models\GameMatch;
use App\Models\Material;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        $materialsCount = Material::where('user_id', $user->id)->sum('quantity');
        $blueprintsCount = Blueprint::where('user_id', $user->id)->count();
        $unitsCount = ForgedUnit::where('user_id', $user->id)->count();
        $teamIds = \App\Models\Team::where('user_id', $user->id)->pluck('id');
        $matchesCount = GameMatch::whereIn('attacker_team_id', $teamIds)
            ->orWhereIn('defender_team_id', $teamIds)
            ->count();

        $status = [
            'materials' => $materialsCount,
            'blueprints' => $blueprintsCount,
            'units' => $unitsCount,
            'matches' => $matchesCount,
            'rating' => $user->rating ?? 1200,
        ];

        return view('dashboard', ['status' => $status, 'user' => $user]);
    }
}
