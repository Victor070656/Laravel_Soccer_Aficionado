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

        return view('matches.show', compact('match'));
    }

    /**
     * Show the match room for live banter and reactions.
     */
    public function room(int $id)
    {
        $raw = $this->api->getFixture($id);

        if (!$raw) {
            abort(404, 'Match not found.');
        }

        $match = (object) FootballApiService::normaliseFixture($raw);

        return view('matches.room', compact('match'));
    }

    /**
     * Currently live fixtures.
     */
    public function live()
    {
        return view('matches.live');
    }
}
