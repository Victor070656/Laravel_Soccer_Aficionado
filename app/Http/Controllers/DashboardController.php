<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Poll;
use App\Services\FootballApiService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected FootballApiService $api,
    ) {
    }

    public function __invoke(Request $request)
    {
        $user = $request->user();

        $feed = Post::with(['user', 'community'])
            ->approved()
            ->feed($user)
            ->take(20)
            ->get();

        $liveMatches = collect($this->api->getLiveFixtures())
            ->map(fn(array $raw) => (object) FootballApiService::normaliseFixture($raw));

        $upcomingMatches = collect($this->api->getUpcomingFixtures(5))
            ->map(fn(array $raw) => (object) FootballApiService::normaliseFixture($raw));

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
