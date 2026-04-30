<?php

namespace App\Livewire\Matches;

use App\Services\FootballApiService;
use Livewire\Component;

class Upcoming extends Component
{
    public function placeholder()
    {
        return <<<'HTML'
        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5 animate-pulse">
            <div class="h-4 w-32 bg-zinc-200 dark:bg-zinc-700 rounded mb-4"></div>
            <div class="space-y-3">
                <div class="h-16 bg-zinc-100 dark:bg-zinc-700/50 rounded-xl"></div>
                <div class="h-16 bg-zinc-100 dark:bg-zinc-700/50 rounded-xl"></div>
                <div class="h-16 bg-zinc-100 dark:bg-zinc-700/50 rounded-xl"></div>
            </div>
        </div>
        HTML;
    }

    public function render(FootballApiService $api)
    {
        $upcomingMatches = collect($api->getUpcomingFixtures(5))
            ->map(fn(array $raw) => (object) FootballApiService::normaliseFixture($raw));

        return view('livewire.matches.upcoming', [
            'upcomingMatches' => $upcomingMatches
        ]);
    }
}
