<?php

namespace App\Livewire\Matches;

use App\Services\FootballApiService;
use Livewire\Component;

class Details extends Component
{
    public int $matchId;

    public function mount(int $id)
    {
        $this->matchId = $id;
    }

    public function render(FootballApiService $api)
    {
        $raw = $api->getFixture($this->matchId);

        if (! $raw) {
            abort(404, 'Match not found.');
        }

        $match = (object) FootballApiService::normaliseFixture($raw);

        $events = collect($api->getFixtureEvents($this->matchId))
            ->map(fn (array $e) => (object) FootballApiService::normaliseEvent($e));

        $lineups = collect($api->getFixtureLineups($this->matchId))
            ->map(fn (array $l) => (object) FootballApiService::normaliseLineup($l));

        $statistics = $api->getFixtureStatistics($this->matchId);

        // Fetch H2H fixtures
        $h2h = collect($api->getH2HFixtures($match->home_team['id'], $match->away_team['id']))
            ->map(fn (array $f) => (object) FootballApiService::normaliseFixture($f));

        // Fetch standings for the league
        $rawStandings = $api->getStandings($match->league['id'], $match->season);
        $standings = collect();
        if (! empty($rawStandings)) {
            $firstGroup = $rawStandings[0] ?? [];
            $standings = collect($firstGroup)
                ->map(fn (array $row) => (object) FootballApiService::normaliseStandingRow($row));
        }

        return view('livewire.matches.details', [
            'match' => $match,
            'events' => $events,
            'lineups' => $lineups,
            'statistics' => $statistics,
            'h2h' => $h2h,
            'standings' => $standings,
        ]);
    }
}
