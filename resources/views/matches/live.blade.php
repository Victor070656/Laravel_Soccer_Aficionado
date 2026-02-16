<x-layouts::app :title="__('Live Matches')">
    <div class="space-y-6">
        <div class="flex items-center gap-3">
            <span class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
            </span>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Live Matches</h1>
        </div>

        <p class="text-xs text-zinc-400">Data refreshes automatically. Last updated: {{ now()->format('H:i:s') }}</p>

        @forelse($matches as $match)
        <a href="{{ route('matches.show', $match->id) }}" class="block rounded-xl border-2 border-red-200 dark:border-red-800 bg-white dark:bg-zinc-800 p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    @if($match->league['logo'] ?? null)
                    <img src="{{ $match->league['logo'] }}" alt="" class="h-4 w-4 object-contain">
                    @endif
                    <span class="text-sm text-zinc-400">{{ $match->league['name'] }}</span>
                </div>
                <span class="text-xs font-mono bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 px-2 py-0.5 rounded-full">
                    {{ $match->elapsed ?? '—' }}'
                </span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex-1 text-center">
                    @if($match->home_team['logo'] ?? null)
                    <img src="{{ $match->home_team['logo'] }}" alt="" class="h-12 w-12 object-contain mx-auto mb-2">
                    @endif
                    <div class="text-xl font-bold text-zinc-900 dark:text-white">{{ $match->home_team['name'] }}</div>
                </div>
                <div class="text-center px-6">
                    <div class="text-4xl font-bold text-red-600">{{ $match->score_display }}</div>
                    <div class="text-xs text-red-500 mt-1">{{ ucfirst(str_replace('_', ' ', $match->status)) }}</div>
                </div>
                <div class="flex-1 text-center">
                    @if($match->away_team['logo'] ?? null)
                    <img src="{{ $match->away_team['logo'] }}" alt="" class="h-12 w-12 object-contain mx-auto mb-2">
                    @endif
                    <div class="text-xl font-bold text-zinc-900 dark:text-white">{{ $match->away_team['name'] }}</div>
                </div>
            </div>
            @if($match->events->count())
            <div class="mt-4 pt-3 border-t border-zinc-100 dark:border-zinc-700">
                @foreach($match->events->take(3) as $event)
                <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $event->time }} {{ $event->icon }} {{ $event->player['name'] }}</div>
                @endforeach
            </div>
            @endif
        </a>
        @empty
        <div class="text-center py-16">
            <div class="text-6xl mb-4">⚽</div>
            <h2 class="text-xl font-bold text-zinc-600 dark:text-zinc-400">No Live Matches</h2>
            <p class="text-zinc-400 mt-2">Check back later for live action!</p>
            <a href="{{ route('matches.index') }}" class="inline-block mt-4 text-sm text-green-600 hover:text-green-700 dark:text-green-400">Browse all matches →</a>
        </div>
        @endforelse
    </div>
</x-layouts::app>
