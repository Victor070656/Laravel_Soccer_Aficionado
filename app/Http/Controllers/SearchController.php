<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Community;
use App\Models\FootballMatch;
use App\Models\Post;
use App\Models\User;
use App\Services\FootballApiService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(
        protected FootballApiService $api,
    ) {
    }

    public function __invoke(Request $request)
    {
        $query = $request->input('q', '');
        $type = $request->input('type', 'all');

        $results = [
            'users' => collect(),
            'clubs' => collect(),
            'communities' => collect(),
            'posts' => collect(),
            'matches' => collect(),
        ];

        if (empty($query)) {
            $users = $results['users'];
            $clubs = $results['clubs'];
            $communities = $results['communities'];
            $posts = $results['posts'];
            $matches = $results['matches'];
            return view('search.index', compact('users', 'clubs', 'communities', 'posts', 'matches', 'query', 'type'));
        }

        if ($type === 'all' || $type === 'users') {
            $results['users'] = User::select(['id', 'name', 'username', 'avatar', 'points'])
                ->where(fn($q) => $q->where('name', 'like', "%{$query}%")
                    ->orWhere('username', 'like', "%{$query}%"))
                ->take(10)->get();
        }

        if ($type === 'all' || $type === 'clubs') {
            $results['clubs'] = collect($this->api->getAllTeams(search: $query))
                ->take(10)
                ->map(fn(array $raw) => (object) FootballApiService::normaliseTeam($raw));
        }

        if ($type === 'all' || $type === 'communities') {
            $results['communities'] = Community::where('is_active', true)
                ->where('name', 'like', "%{$query}%")
                ->withCount('members')
                ->take(10)->get();
        }

        if ($type === 'all' || $type === 'posts') {
            $results['posts'] = Post::approved()
                ->where('body', 'like', "%{$query}%")
                ->with('user')
                ->take(10)->get();
        }

        $users = $results['users'];
        $clubs = $results['clubs'];
        $communities = $results['communities'];
        $posts = $results['posts'];
        $matches = collect(); // Matches come from API, not local DB

        return view('search.index', compact('users', 'clubs', 'communities', 'posts', 'matches', 'query', 'type'));
    }
}
