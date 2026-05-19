<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\Post;
use App\Models\User;
use App\Services\FootballApiService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(
        protected FootballApiService $api,
    ) {}

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
                ->where(fn ($q) => $q->where('name', 'like', "%{$query}%")
                    ->orWhere('username', 'like', "%{$query}%"))
                ->take(10)->get();
        }

        if ($type === 'all' || $type === 'clubs') {
            $results['clubs'] = collect($this->api->getAllTeams(search: $query))
                ->take(10)
                ->map(fn (array $raw) => (object) FootballApiService::normaliseTeam($raw));
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

        // Search matches via API – search all fixtures and filter by team name
        $matches = collect();
        if ($type === 'all' || $type === 'matches') {
            $result = $this->api->getAllFixtures();
            $allFixtures = collect($result['data'] ?? [])
                ->map(fn (array $raw) => (object) FootballApiService::normaliseFixture($raw));

            $matches = $allFixtures->filter(function ($fixture) use ($query) {
                $q = mb_strtolower($query);
                $home = $fixture->home_team['name'] ?? '';
                $away = $fixture->away_team['name'] ?? '';
                $league = $fixture->league['name'] ?? '';

                return str_contains(mb_strtolower($home), $q)
                    || str_contains(mb_strtolower($away), $q)
                    || str_contains(mb_strtolower($league), $q);
            })->take(10)->values();
        }

        return view('search.index', compact('users', 'clubs', 'communities', 'posts', 'matches', 'query', 'type'));
    }
}
