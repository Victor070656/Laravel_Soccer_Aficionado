<?php

namespace App\Http\Controllers;

use App\Services\FootballApiService;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    public function __construct(
        protected FootballApiService $api,
    ) {}

    public function index(Request $request)
    {
        $allTeams = $this->api->getAllTeams(
            search: $request->input('search'),
            country: $request->input('country'),
        );

        $teams = collect($allTeams)
            ->map(fn (array $raw) => (object) FootballApiService::normaliseTeam($raw));

        // Simple pagination
        $page = max(1, (int) $request->input('page', 1));
        $perPage = 24;
        $total = $teams->count();
        $paginatedTeams = $teams->slice(($page - 1) * $perPage, $perPage)->values();

        $leagues = collect($this->api->getLeagues())->map(fn (array $l) => (object) $l);

        return view('clubs.index', [
            'clubs' => $paginatedTeams,
            'leagues' => $leagues,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => max(1, (int) ceil($total / $perPage)),
                'has_more' => ($page * $perPage) < $total,
            ],
            'apiConfigured' => $this->api->isConfigured(),
        ]);
    }

    public function show(int $id)
    {
        $raw = $this->api->getTeam($id);

        if (! $raw) {
            abort(404, 'Club not found.');
        }

        $club = (object) FootballApiService::normaliseTeam($raw);

        // Squad
        $squad = collect($this->api->getTeamSquad($id))
            ->map(fn (array $p) => (object) FootballApiService::normaliseSquadPlayer($p));

        // Recent results
        $recentMatches = collect($this->api->getTeamFixtures($id, 'finished', 5))
            ->map(fn (array $raw) => (object) FootballApiService::normaliseFixture($raw));

        // Upcoming matches
        $upcomingMatches = collect($this->api->getTeamFixtures($id, 'upcoming', 5))
            ->map(fn (array $raw) => (object) FootballApiService::normaliseFixture($raw));

        return view('clubs.show', compact('club', 'squad', 'recentMatches', 'upcomingMatches'));
    }
}
