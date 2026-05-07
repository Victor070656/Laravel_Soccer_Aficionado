<?php

namespace App\Livewire\Matches;

use Livewire\Component;

class NavCount extends Component
{
    public function render()
    {
        // Count live matches for bottom nav badge
        $liveMatches = app(\App\Services\FootballApiService::class)->getLiveFixtures();
        $liveCount = count($liveMatches);

        return view('livewire.matches.nav-count', [
            'liveMatchCount' => $liveCount,
        ]);
    }
}
