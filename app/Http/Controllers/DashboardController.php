<?php

namespace App\Http\Controllers;

use App\Models\FootballMatch;
use App\Models\Post;
use App\Models\Club;
use App\Models\Poll;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $feed = Post::with(['user', 'community'])
            ->approved()
            ->feed($user)
            ->take(20)
            ->get();

        $liveMatches = FootballMatch::with(['homeClub', 'awayClub', 'competition'])
            ->live()
            ->get();

        $upcomingMatches = FootballMatch::with(['homeClub', 'awayClub', 'competition'])
            ->upcoming()
            ->take(5)
            ->get();

        $activePolls = Poll::with('options')
            ->active()
            ->latest()
            ->take(3)
            ->get();

        $trendingPosts = Post::with('user')
            ->trending()
            ->take(5)
            ->get();

        $favoriteClubs = $user->favoriteClubs;

        return view('dashboard', compact(
            'feed',
            'liveMatches',
            'upcomingMatches',
            'activePolls',
            'trendingPosts',
            'favoriteClubs'
        ));
    }
}
