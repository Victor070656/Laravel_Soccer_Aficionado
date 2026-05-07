<?php

namespace App\Livewire\Matches;

use App\Services\FootballApiService;
use Livewire\Component;

class LivePage extends Component
{
    public function render(FootballApiService $api)
    {
        $rawFixtures = $api->getLiveFixtures();

        $matches = collect($rawFixtures)
            ->map(function (array $raw) use ($api) {
                $match = (object) FootballApiService::normaliseFixture($raw);
                // Attach recent events for each live match
                $match->events = collect($api->getFixtureEvents($match->id))
                    ->map(fn(array $e) => (object) FootballApiService::normaliseEvent($e));
                return $match;
            });

        return view('livewire.matches.live-page', compact('matches'));
    }
}
