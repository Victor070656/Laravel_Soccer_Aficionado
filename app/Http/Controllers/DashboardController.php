<?php

namespace App\Http\Controllers;

use App\Actions\Posts\CreatePost;
use App\Models\Poll;
use App\Models\Post;
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
        $user->load('favoriteClubs');

        $feed = Post::with(['user', 'community'])
            ->withCount(['likes', 'comments', 'shares'])
            ->approved()
            ->feed($user)
            ->paginate(20);

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

        $favoriteClubs = $user->favoriteClubs;

        return view('dashboard', compact(
            'feed',
            'activePolls',
            'trendingPosts',
            'favoriteClubs'
        ))->with('postTypes', CreatePost::types());
    }
}
