<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\FootballMatch;
use App\Services\FootballApiService;
use Illuminate\Http\Request;

/**
 * Admin management for matches sourced from TheSportsDB API.
 *
 * Matches are NOT created manually – they are fetched from the API.
 * Admins can: browse API data, sync fixtures into the local DB, and view details.
 */
class MatchManagementController extends Controller
{
    public function __construct(
        protected FootballApiService $api,
    ) {
    }

    /**
     * Browse matches from the API with filtering.
     */
    public function index(Request $request)
    {
        $status = $request->input('status');
        $league = $request->input('league');
        $date = $request->input('date');

        $result = $this->api->getAllFixtures([
            'status' => $status,
            'league' => $league,
            'date' => $date,
            'page' => $request->input('page', 1),
        ]);

        $matches = collect($result['data'] ?? [])
            ->map(fn(array $raw) => (object) FootballApiService::normaliseFixture($raw));

        $leagues = collect($this->api->getLeagues())
            ->map(fn(array $l) => (object) $l);

        return view('admin.matches.index', compact('matches', 'leagues', 'result'));
    }

    /**
     * Show a single match detail from the API.
     */
    public function show(int $id)
    {
        $raw = $this->api->getFixture($id);

        if (!$raw) {
            abort(404, 'Match not found.');
        }

        $match = (object) FootballApiService::normaliseFixture($raw);

        return view('admin.matches.show', compact('match'));
    }

    /**
     * Sync matches from the API for a given date into the local DB.
     *
     * Imports fixtures so they can be linked to polls, communities, etc.
     */
    public function syncFromApi(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $fixtures = $this->api->getFixturesByDate($date);

        $synced = 0;
        foreach ($fixtures as $fixture) {
            $normalised = FootballApiService::normaliseFixture($fixture);

            // Find or create local club records from API data
            $homeClub = Club::fromApiTeam(FootballApiService::normaliseTeam([
                'team' => $normalised['home_team'],
                'venue' => ['id' => null, 'name' => $normalised['venue'] ?? null, 'city' => null, 'capacity' => null, 'surface' => null, 'image' => null],
            ]));

            $awayClub = Club::fromApiTeam(FootballApiService::normaliseTeam([
                'team' => $normalised['away_team'],
                'venue' => ['id' => null, 'name' => null, 'city' => null, 'capacity' => null, 'surface' => null, 'image' => null],
            ]));

            FootballMatch::updateOrCreate(
                ['api_match_id' => $normalised['id']],
                [
                    'home_club_id' => $homeClub->id,
                    'away_club_id' => $awayClub->id,
                    'kick_off' => $normalised['date'] ?? now(),
                    'status' => $normalised['status'] ?? 'scheduled',
                    'home_score' => $normalised['home_score'] ?? null,
                    'away_score' => $normalised['away_score'] ?? null,
                    'venue' => $normalised['venue'] ?? null,
                    'round' => $normalised['league']['round'] ?? null,
                ]
            );
            $synced++;
        }

        return back()->with('success', "Synced {$synced} matches from API for {$date}.");
    }

    /**
     * Sync all teams for a given league into local clubs.
     */
    public function syncTeams(Request $request)
    {
        $leagueId = $request->input('league_id');

        if (!$leagueId) {
            return back()->with('error', 'Please select a league to sync teams from.');
        }

        $teams = $this->api->getTeamsByLeague((int) $leagueId);
        $synced = 0;

        foreach ($teams as $raw) {
            $team = FootballApiService::normaliseTeam($raw);
            Club::fromApiTeam($team);
            $synced++;
        }

        return back()->with('success', "Synced {$synced} clubs from the API.");
    }
}
