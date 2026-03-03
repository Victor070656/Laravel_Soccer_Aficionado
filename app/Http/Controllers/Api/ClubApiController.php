<?php

namespace App\Http\Controllers\Api;

use App\Services\FootballApiService;
use Illuminate\Http\Request;

class ClubApiController extends BaseApiController
{
    public function __construct(
        protected FootballApiService $api,
    ) {
    }

    public function index(Request $request)
    {
        $allTeams = $this->api->getAllTeams(
            search: $request->input('search'),
            country: $request->input('country'),
        );

        $teams = collect($allTeams)
            ->map(fn(array $raw) => (object) FootballApiService::normaliseTeam($raw));

        // Simple pagination
        $page = max(1, (int) $request->input('page', 1));
        $perPage = 24;
        $total = count($teams);
        $offset = ($page - 1) * $perPage;
        $paginatedTeams = $teams->slice($offset, $perPage)->values();

        return $this->success([
            'data' => $paginatedTeams,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => max(1, (int) ceil($total / $perPage)),
            'has_more' => ($page * $perPage) < $total,
        ]);
    }

    public function show(int $id)
    {
        $raw = $this->api->getTeam($id);

        if (!$raw) {
            return $this->error('Club not found.', 404);
        }

        $club = FootballApiService::normaliseTeam($raw);

        // Squad
        $club['squad'] = array_map(
            fn(array $p) => FootballApiService::normaliseSquadPlayer($p),
            $this->api->getTeamSquad($id),
        );

        // Recent results
        $club['recent_matches'] = array_map(
            fn(array $raw) => FootballApiService::normaliseFixture($raw),
            $this->api->getTeamFixtures($id, 'finished', 5),
        );

        // Upcoming matches
        $club['upcoming_matches'] = array_map(
            fn(array $raw) => FootballApiService::normaliseFixture($raw),
            $this->api->getTeamFixtures($id, 'upcoming', 5),
        );

        return $this->success($club);
    }
}
