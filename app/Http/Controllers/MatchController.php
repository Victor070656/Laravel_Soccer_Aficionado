<?php

namespace App\Http\Controllers;

use App\Services\FootballApiService;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function __construct(
        protected FootballApiService $api,
    ) {
    }

    /**
     * List fixtures – supports filters: status, league, date, page.
     */
    public function index(Request $request)
    {
        $result = $this->api->getAllFixtures([
            'status' => $request->input('status'),
            'league' => $request->input('league'),
            'date' => $request->input('date'),
            'page' => $request->input('page', 1),
        ]);

        $matches = collect($result['data'] ?? [])
            ->map(fn(array $raw) => (object) FootballApiService::normaliseFixture($raw));

        $leagues = collect($this->api->getLeagues())
            ->map(fn(array $l) => (object) $l);

        return view('matches.index', [
            'matches' => $matches,
            'leagues' => $leagues,
            'pagination' => $result,
            'apiConfigured' => $this->api->isConfigured(),
        ]);
    }

    /**
     * Show a single fixture with events, lineups, statistics.
     */
    public function show(int $id)
    {
        $raw = $this->api->getFixture($id);

        if (!$raw) {
            abort(404, 'Match not found.');
        }

        $match = (object) FootballApiService::normaliseFixture($raw);

        $events = collect($this->api->getFixtureEvents($id))
            ->map(fn(array $e) => (object) FootballApiService::normaliseEvent($e));

        $lineups = collect($this->api->getFixtureLineups($id))
            ->map(fn(array $l) => (object) FootballApiService::normaliseLineup($l));

        $statistics = $this->api->getFixtureStatistics($id);

        // Fetch H2H fixtures
        $h2h = collect($this->api->getH2HFixtures($match->home_team['id'], $match->away_team['id']))
            ->map(fn(array $f) => (object) FootballApiService::normaliseFixture($f));

        // Fetch standings for the league
        $rawStandings = $this->api->getStandings($match->league['id'], $match->season);
        $standings = collect();
        if (!empty($rawStandings)) {
            $firstGroup = $rawStandings[0] ?? [];
            $standings = collect($firstGroup)
                ->map(fn(array $row) => (object) FootballApiService::normaliseStandingRow($row));
        }

        return view('matches.show', compact('match', 'events', 'lineups', 'statistics', 'h2h', 'standings'));
    }

    /**
     * Currently live fixtures.
     */
    public function live()
    {
        $rawFixtures = $this->api->getLiveFixtures();

        $matches = collect($rawFixtures)
            ->map(function (array $raw) {
                $match = (object) FootballApiService::normaliseFixture($raw);
                // Attach recent events for each live match
                $match->events = collect($this->api->getFixtureEvents($match->id))
                    ->map(fn(array $e) => (object) FootballApiService::normaliseEvent($e));
                return $match;
            });

        return view('matches.live', compact('matches'));
    }
}
