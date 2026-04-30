<?php

namespace App\Livewire\Matches;

use App\Services\FootballApiService;
use Livewire\Component;

class Live extends Component
{
    public function placeholder()
    {
        return <<<'HTML'
        <div class="rounded-2xl bg-zinc-50 dark:bg-zinc-800/50 p-6 animate-pulse border border-zinc-200 dark:border-zinc-700">
            <div class="h-6 w-32 bg-zinc-200 dark:bg-zinc-700 rounded mb-4"></div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="h-24 bg-zinc-200 dark:bg-zinc-700 rounded-xl"></div>
                <div class="h-24 bg-zinc-200 dark:bg-zinc-700 rounded-xl"></div>
                <div class="h-24 bg-zinc-200 dark:bg-zinc-700 rounded-xl"></div>
            </div>
        </div>
        HTML;
    }

    public function render(FootballApiService $api)
    {
        $liveMatches = collect($api->getLiveFixtures())
            ->map(fn(array $raw) => (object) FootballApiService::normaliseFixture($raw));

        return view('livewire.matches.live', [
            'liveMatches' => $liveMatches
        ]);
    }
}
