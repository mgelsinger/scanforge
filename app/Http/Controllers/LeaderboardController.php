<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    public function __invoke(): View
    {
        $leaders = Cache::remember('leaderboard.users', 60, function () {
            return User::orderByDesc('rating')
                ->orderBy('name')
                ->take(20)
                ->get(['id', 'name', 'rating', 'email']);
        });

        return view('leaderboard.index', ['leaders' => $leaders]);
    }
}
