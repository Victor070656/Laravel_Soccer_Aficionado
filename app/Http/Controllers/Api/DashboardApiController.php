<?php

namespace App\Http\Controllers\Api;

use App\Models\Poll;
use App\Models\Post;
use App\Services\FootballApiService;
use Illuminate\Http\Request;

class DashboardApiController extends BaseApiController
{
    public function __construct(
        protected FootballApiService $api,
    ) {
    }

    public function __invoke(Request $request)
    {
        $user = $request->user();
        $user->load('favoriteClubs');

        $feed = Post::with(['user', 'community'])
            ->withCount(['likes', 'comments', 'shares'])
            ->approved()
            ->feed($user)
            ->paginate(20);

        $liveMatches = array_map(
            fn(array $raw) => FootballApiService::normaliseFixture($raw),
            $this->api->getLiveFixtures(),
        );

        $upcomingMatches = array_map(
            fn(array $raw) => FootballApiService::normaliseFixture($raw),
            $this->api->getUpcomingFixtures(5),
        );

        $activePolls = Poll::with('options')
            ->active()
            ->latest()
            ->take(3)
            ->get();

        $trendingPosts = Post::with(['user', 'community'])
            ->withCount(['likes', 'comments', 'shares'])
            ->trending()
            ->take(5)
            ->get();

        return $this->success([
            'feed' => $feed,
            'live_matches' => $liveMatches,
            'upcoming_matches' => $upcomingMatches,
            'active_polls' => $activePolls,
            'trending_posts' => $trendingPosts,
            'favorite_clubs' => $user->favoriteClubs,
        ]);
    }
}
