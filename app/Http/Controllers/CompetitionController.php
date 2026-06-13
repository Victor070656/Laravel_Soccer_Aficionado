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

    public function show(Request $request, int $id)
    {
        $league = $this->api->getLeague($id);

        if (!$league) {
            abort(404, 'Competition not found.');
        }

        $competition = (object) $league;
        $currentChampion = $this->api->getCurrentChampion($id);

        // Determine which season to show
        $currentSeason = $this->api->getSeason();
        $selectedSeason = $request->query('season', $currentSeason);

        // Validate season format (e.g. "2024-2025")
        if (!preg_match('/^\d{4}-\d{4}$/', $selectedSeason)) {
            $selectedSeason = $currentSeason;
        }

        $isCurrentSeason = $selectedSeason === $currentSeason;

        $availableSeasons = $this->api->getLeagueSeasons($id);
        if (empty($availableSeasons)) {
            $availableSeasons = [
                [
                    'value' => $currentSeason,
                    'label' => $this->api->seasonDisplay(),
                ]
            ];
        }

        // Standings (may be grouped) — use selected season
        $rawStandings = $this->api->getStandings($id, $selectedSeason);
        $standings = collect();
        if (!empty($rawStandings)) {
            // Some leagues might return a flat list or nested structure.
            // Normalize to a single list of standings rows.
            $flattened = [];
            foreach ($rawStandings as $group) {
                if (is_array($group)) {
                    foreach ($group as $row) {
                        $flattened[] = $row;
                    }
                }
            }

            $standings = collect($flattened)
                ->map(fn(array $row) => (object) FootballApiService::normaliseStandingRow($row));
        }

        // Upcoming fixtures & recent results only make sense for the current season
        $upcomingMatches = collect();
        $recentResults = collect();
        if ($isCurrentSeason) {
            $upcomingMatches = collect($this->api->getUpcomingFixtures(10, $id))
                ->map(fn(array $raw) => (object) FootballApiService::normaliseFixture($raw));

            $recentResults = collect($this->api->getFinishedFixtures(10, $id))
                ->map(fn(array $raw) => (object) FootballApiService::normaliseFixture($raw));
        }

        // Teams in this league
        $teams = collect($this->api->getTeamsByLeague($id))
            ->map(fn(array $raw) => (object) FootballApiService::normaliseTeam($raw));

        // Build display string for the selected season
        $parts = explode('-', $selectedSeason);
        $seasonDisplay = count($parts) === 2
            ? $parts[0] . '/' . substr($parts[1], -2)
            : $selectedSeason;

        return view('competitions.show', compact(
            'competition',
            'currentChampion',
            'standings',
            'upcomingMatches',
            'recentResults',
            'teams',
            'availableSeasons',
            'selectedSeason',
            'isCurrentSeason',
        ) + [
            'seasonDisplay' => $seasonDisplay,
        ]);
    }
}
