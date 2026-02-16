<?php

namespace App\Http\Controllers;

use App\Services\FootballApiService;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    public function __construct(
        protected FootballApiService $api,
    ) {
    }

    public function index()
    {
        $competitions = collect($this->api->getLeaguesFull())
            ->map(fn(array $l) => (object) $l);

        return view('competitions.index', [
            'competitions' => $competitions,
            'seasonDisplay' => $this->api->seasonDisplay(),
            'apiConfigured' => $this->api->isConfigured(),
        ]);
    }

    public function show(int $id)
    {
        $league = $this->api->getLeague($id);

        if (!$league) {
            abort(404, 'Competition not found.');
        }

        $competition = (object) $league;

        // Standings (may be grouped)
        $rawStandings = $this->api->getStandings($id);
        $standings = collect();
        if (!empty($rawStandings)) {
            // Flatten all groups into a single collection for single-group leagues
            // or keep first group for display
            $firstGroup = $rawStandings[0] ?? [];
            $standings = collect($firstGroup)
                ->map(fn(array $row) => (object) FootballApiService::normaliseStandingRow($row));
        }

        // Upcoming fixtures for this league
        $upcomingMatches = collect($this->api->getUpcomingFixtures(10, $id))
            ->map(fn(array $raw) => (object) FootballApiService::normaliseFixture($raw));

        // Recent results for this league
        $recentResults = collect($this->api->getFinishedFixtures(10, $id))
            ->map(fn(array $raw) => (object) FootballApiService::normaliseFixture($raw));

        // Teams in this league
        $teams = collect($this->api->getTeamsByLeague($id))
            ->map(fn(array $raw) => (object) FootballApiService::normaliseTeam($raw));

        return view('competitions.show', compact('competition', 'standings', 'upcomingMatches', 'recentResults', 'teams') + [
            'seasonDisplay' => $this->api->seasonDisplay(),
        ]);
    }
}
