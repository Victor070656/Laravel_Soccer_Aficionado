<?php

namespace App\Http\Controllers\Api;

use App\Models\Club;
use App\Models\Community;
use App\Models\FootballMatch;
use App\Models\Post;
use App\Models\User;
use App\Services\FootballApiService;
use Illuminate\Http\Request;

class SearchApiController extends BaseApiController
{
    public function __construct(
        protected FootballApiService $api,
    ) {
    }

    public function __invoke(Request $request)
    {
        $query = $request->input('q', '');
        $type = $request->input('type', 'all');

        $results = [];

        if (empty($query)) {
            return $this->success([
                'users' => [],
                'clubs' => [],
                'communities' => [],
                'posts' => [],
                'matches' => [],
            ]);
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
                ->map(fn(array $raw) => FootballApiService::normaliseTeam($raw))
                ->values();
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
                ->withCount(['likes', 'comments', 'shares'])
                ->take(10)->get();
        }

        if ($type === 'all' || $type === 'matches') {
            $allFixtures = collect($this->api->getAllFixtures())
                ->map(fn(array $raw) => FootballApiService::normaliseFixture($raw));

            $results['matches'] = $allFixtures->filter(function ($fixture) use ($query) {
                $q = mb_strtolower($query);
                return str_contains(mb_strtolower($fixture['home_team'] ?? ''), $q)
                    || str_contains(mb_strtolower($fixture['away_team'] ?? ''), $q)
                    || str_contains(mb_strtolower($fixture['league'] ?? ''), $q);
            })->take(10)->values();
        }

        return $this->success($results);
    }
}
