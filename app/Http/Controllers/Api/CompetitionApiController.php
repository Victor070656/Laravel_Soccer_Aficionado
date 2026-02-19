<?php

namespace App\Http\Controllers\Api;

use App\Services\FootballApiService;
use Illuminate\Http\Request;

class CompetitionApiController extends BaseApiController
{
    public function __construct(
        protected FootballApiService $api,
    ) {
    }

    public function index()
    {
        $competitions = $this->api->getLeaguesFull();

        return $this->success([
            'data' => $competitions,
            'season_display' => $this->api->seasonDisplay(),
        ]);
    }

    public function show(Request $request, int $id)
    {
        $league = $this->api->getLeague($id);

        if (!$league) {
            return $this->error('Competition not found.', 404);
        }

        // Determine which season to show
        $currentSeason = $this->api->getSeason();
        $selectedSeason = $request->query('season', $currentSeason);

        // Validate season format (e.g. "2024-2025")
        if (!preg_match('/^\d{4}-\d{4}$/', $selectedSeason)) {
            $selectedSeason = $currentSeason;
        }

        $isCurrentSeason = $selectedSeason === $currentSeason;

        // Build list of available seasons (current + 4 previous)
        $currentYear = (int) explode('-', $currentSeason)[0];
        $availableSeasons = [];
        for ($y = $currentYear; $y >= $currentYear - 4; $y--) {
            $season = $y . '-' . ($y + 1);
            $availableSeasons[] = [
                'value' => $season,
                'label' => $y . '/' . substr((string) ($y + 1), -2),
            ];
        }

        // Standings — use selected season
        $rawStandings = $this->api->getStandings($id, $selectedSeason);
        $standings = [];
        if (!empty($rawStandings)) {
            $firstGroup = $rawStandings[0] ?? [];
            $standings = array_map(
                fn(array $row) => FootballApiService::normaliseStandingRow($row),
                $firstGroup,
            );
        }

        // Upcoming fixtures & recent results only for current season
        $upcomingMatches = [];
        $recentResults = [];
        if ($isCurrentSeason) {
            $upcomingMatches = array_map(
                fn(array $raw) => FootballApiService::normaliseFixture($raw),
                $this->api->getUpcomingFixtures(10, $id),
            );

            $recentResults = array_map(
                fn(array $raw) => FootballApiService::normaliseFixture($raw),
                $this->api->getFinishedFixtures(10, $id),
            );
        }

        // Teams in this league
        $teams = array_map(
            fn(array $raw) => FootballApiService::normaliseTeam($raw),
            $this->api->getTeamsByLeague($id),
        );

        // Build display string for the selected season
        $parts = explode('-', $selectedSeason);
        $seasonDisplay = count($parts) === 2
            ? $parts[0] . '/' . substr($parts[1], -2)
            : $selectedSeason;

        return $this->success([
            'competition' => $league,
            'standings' => $standings,
            'upcoming_matches' => $upcomingMatches,
            'recent_results' => $recentResults,
            'teams' => $teams,
            'available_seasons' => $availableSeasons,
            'selected_season' => $selectedSeason,
            'is_current_season' => $isCurrentSeason,
            'season_display' => $seasonDisplay,
        ]);
    }
}
