<?php

namespace App\Http\Controllers\Api;

use App\Concerns\AppendsPostFlags;
use App\Models\Poll;
use App\Models\Post;
use App\Services\FootballApiService;
use Illuminate\Http\Request;

class DashboardApiController extends BaseApiController
{
    use AppendsPostFlags;

    public function __construct(
        protected FootballApiService $api,
    ) {
    }

    public function __invoke(Request $request)
    {
        $user = $request->user();
        $user->load('favoriteClubs');

        $feed = Post::with(['user', 'community'])
            ->withCount(['reactions as likes_count', 'comments', 'shares'])
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

        $activePolls = Poll::with(['options', 'user'])
            ->active()
            ->latest()
            ->take(3)
            ->get();

        // Append per-user vote state, author alias, and set inverse relation
        // on options so the percentage accessor doesn't N+1 query the parent poll.
        $activePolls->transform(function ($poll) use ($user) {
            $vote = $poll->votes()->where('user_id', $user->id)->first();
            $poll->user_vote = $vote?->poll_option_id ?? null;
            $poll->created_by = $poll->user;
            $poll->options->each(fn($opt) => $opt->setRelation('poll', $poll));

            return $poll;
        });

        $trendingPosts = Post::with(['user', 'community'])
            ->withCount(['reactions as likes_count', 'comments', 'shares'])
            ->trending()
            ->take(5)
            ->get();

        $this->appendPostFlags($feed, $user);
        $this->appendPostFlags($trendingPosts, $user);

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
