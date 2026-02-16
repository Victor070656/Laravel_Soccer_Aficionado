<x-layouts::app :title="__('Competitions')">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Competitions</h1>
            <span class="text-sm text-zinc-400">Season {{ $seasonDisplay }}</span>
        </div>

        @unless($apiConfigured)
        <div class="rounded-lg bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 p-4 text-sm text-yellow-700 dark:text-yellow-400">
            ⚠️ Football API key is not configured. Add <code>FOOTBALL_API_KEY</code> to your <code>.env</code> file to see competition data.
        </div>
        @endunless

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($competitions as $competition)
            <a href="{{ route('competitions.show', $competition->id) }}" class="block rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4 hover:shadow-md transition group">
                <div class="flex items-center gap-3 mb-2">
                    @if($competition->logo)
                    <img src="{{ $competition->logo }}" alt="{{ $competition->name }}" class="w-10 h-10 object-contain">
                    @else
                    <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400 font-bold text-sm">
                        {{ strtoupper(substr($competition->name, 0, 2)) }}
                    </div>
                    @endif
                    <div>
                        <div class="font-semibold text-zinc-900 dark:text-white group-hover:text-green-600">{{ $competition->name }}</div>
                        <div class="flex items-center gap-1 text-xs text-zinc-400">
                            @if($competition->country_flag ?? null)
                            <img src="{{ $competition->country_flag }}" alt="" class="h-3 w-4 object-contain">
                            @endif
                            {{ ucfirst($competition->type) }} · {{ $competition->country ?? 'International' }}
                        </div>
                    </div>
                </div>
                <div class="text-xs text-zinc-400">Season: {{ $competition->season }}/{{ substr((string)($competition->season + 1), -2) }}</div>
                @if($competition->season_start && $competition->season_end)
                <div class="text-xs text-zinc-400 mt-0.5">{{ $competition->season_start }} → {{ $competition->season_end }}</div>
                @endif
            </a>
            @empty
            <div class="col-span-full text-center py-12 text-zinc-400">
                @if($apiConfigured)
                <div class="text-4xl mb-3">🏆</div>
                <p>No competitions found.</p>
                @else
                <div class="text-4xl mb-3">🔌</div>
                <p>Configure your API key to see competition data.</p>
                @endif
            </div>
            @endforelse
        </div>
    </div>
</x-layouts::app>
