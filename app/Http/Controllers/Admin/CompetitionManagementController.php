<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FootballApiService;

/**
 * Admin management for competitions sourced from TheSportsDB API.
 *
 * Competitions (leagues) are pre-configured in services.football_api.leagues.
 * Admins can: view league details, standings, and API sync status.
 */
class CompetitionManagementController extends Controller
{
    public function __construct(
        protected FootballApiService $api,
    ) {}

    /**
     * List all configured competitions from the API.
     */
    public function index()
    {
        $competitions = collect($this->api->getLeaguesFull())
            ->map(fn (array $l) => (object) $l);

        return view('admin.competitions.index', compact('competitions') + [
            'seasonDisplay' => $this->api->seasonDisplay(),
        ]);
    }

    /**
     * Show a single competition with standings, fixtures, and teams.
     */
    public function show(int $id)
    {
        $league = $this->api->getLeague($id);

        if (! $league) {
            abort(404, 'Competition not found.');
        }

        $competition = (object) $league;

        // Standings
        $rawStandings = $this->api->getStandings($id);
        $standings = collect();
        if (! empty($rawStandings)) {
            $firstGroup = $rawStandings[0] ?? [];
            $standings = collect($firstGroup)
                ->map(fn (array $row) => (object) FootballApiService::normaliseStandingRow($row));
        }

        // Teams
        $teams = collect($this->api->getTeamsByLeague($id))
            ->map(fn (array $raw) => (object) FootballApiService::normaliseTeam($raw));

        // Upcoming matches
        $upcomingMatches = collect($this->api->getUpcomingFixtures(10, $id))
            ->map(fn (array $raw) => (object) FootballApiService::normaliseFixture($raw));

        // Recent results
        $recentResults = collect($this->api->getFinishedFixtures(10, $id))
            ->map(fn (array $raw) => (object) FootballApiService::normaliseFixture($raw));

        return view('admin.competitions.show', compact('competition', 'standings', 'teams', 'upcomingMatches', 'recentResults') + [
            'seasonDisplay' => $this->api->seasonDisplay(),
        ]);
    }
}
