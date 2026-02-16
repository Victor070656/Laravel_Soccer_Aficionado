<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\GamificationService;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function __construct(
        protected GamificationService $gamification,
    ) {
    }

    public function __invoke()
    {
        $topUsers = $this->gamification->getLeaderboard(50);

        $currentUser = auth()->user();
        $currentUserRank = User::where('points', '>', $currentUser->points)->count() + 1;

        return view('leaderboard.index', compact('topUsers', 'currentUser', 'currentUserRank'));
    }
}
