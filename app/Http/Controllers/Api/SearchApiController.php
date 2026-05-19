<?php

namespace App\Http\Controllers\Api;

use App\Models\Community;
use App\Models\Post;
use App\Models\User;
use App\Services\FootballApiService;
use Illuminate\Http\Request;

class SearchApiController extends BaseApiController
{
    public function __construct(
        protected FootballApiService $api,
    ) {}

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
                ->where(fn ($q) => $q->where('name', 'like', "%{$query}%")
                    ->orWhere('username', 'like', "%{$query}%"))
                ->take(10)->get();
        }

        if ($type === 'all' || $type === 'clubs') {
            $results['clubs'] = collect($this->api->getAllTeams(search: $query))
                ->take(10)
                ->map(fn (array $raw) => FootballApiService::normaliseTeam($raw))
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
            $fixturesPage = $this->api->getAllFixtures();
            $allFixtures = collect($fixturesPage['data'] ?? [])
                ->map(fn (array $raw) => FootballApiService::normaliseFixture($raw));

            $results['matches'] = $allFixtures->filter(function ($fixture) use ($query) {
                $q = mb_strtolower($query);
                $home = $fixture['home_team']['name'] ?? '';
                $away = $fixture['away_team']['name'] ?? '';
                $league = $fixture['league']['name'] ?? '';

                return str_contains(mb_strtolower($home), $q)
                    || str_contains(mb_strtolower($away), $q)
                    || str_contains(mb_strtolower($league), $q);
            })->take(10)->values();
        }

        return $this->success($results);
    }
}
