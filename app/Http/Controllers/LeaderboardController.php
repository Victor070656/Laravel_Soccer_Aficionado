<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\GamificationService;

class LeaderboardController extends Controller
{
    public function __construct(
        protected GamificationService $gamification,
    ) {}

    public function __invoke()
    {
        $leaders = $this->gamification->getLeaderboard(50);

        $currentUser = auth()->user();
        $currentRank = User::where('points', '>', $currentUser->points)->count() + 1;

        return view('leaderboard.index', compact('leaders', 'currentUser', 'currentRank'));
    }
}
