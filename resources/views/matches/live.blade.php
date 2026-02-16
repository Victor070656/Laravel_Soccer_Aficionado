<x-layouts::app :title="__('Live Matches')">
    <div class="space-y-6">
        <div class="flex items-center gap-3">
            <span class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
            </span>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Live Matches</h1>
        </div>

        @forelse($matches as $match)
        <a href="{{ route('matches.show', $match) }}" class="block rounded-xl border-2 border-red-200 dark:border-red-800 bg-white dark:bg-zinc-800 p-6 hover:shadow-lg transition">
            <div class="text-sm text-zinc-400 mb-3">{{ $match->competition->name }}</div>
            <div class="flex items-center justify-between">
                <div class="flex-1 text-center">
                    <div class="text-xl font-bold text-zinc-900 dark:text-white">{{ $match->homeClub->name }}</div>
                </div>
                <div class="text-center px-6">
                    <div class="text-4xl font-bold text-red-600">{{ $match->score_display }}</div>
                    <div class="text-xs text-red-500 mt-1">{{ ucfirst(str_replace('_', ' ', $match->status)) }}</div>
                </div>
                <div class="flex-1 text-center">
                    <div class="text-xl font-bold text-zinc-900 dark:text-white">{{ $match->awayClub->name }}</div>
                </div>
            </div>
            @if($match->events->count())
            <div class="mt-4 pt-3 border-t border-zinc-100 dark:border-zinc-700">
                @foreach($match->events->take(3) as $event)
                <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $event->time_display }} {{ $event->icon }} {{ $event->player?->name }}</div>
                @endforeach
            </div>
            @endif
        </a>
        @empty
        <div class="text-center py-16">
            <div class="text-6xl mb-4">⚽</div>
            <h2 class="text-xl font-bold text-zinc-600 dark:text-zinc-400">No Live Matches</h2>
            <p class="text-zinc-400 mt-2">Check back later for live action!</p>
        </div>
        @endforelse
    </div>
</x-layouts::app>
