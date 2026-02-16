<x-layouts::app :title="__('Clubs')">
    <div class="space-y-6">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Clubs</h1>

        @unless($apiConfigured)
        <div class="rounded-lg bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 p-4 text-sm text-yellow-700 dark:text-yellow-400">
            ⚠️ Football API key is not configured. Add <code>FOOTBALL_API_KEY</code> to your <code>.env</code> file to see club data.
        </div>
        @endunless

        <form method="GET" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search clubs..." class="flex-1 rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm">
            <input type="text" name="country" value="{{ request('country') }}" placeholder="Country" class="rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm w-40">
            <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-700">Search</button>
            @if(request()->hasAny(['search', 'country']))
            <a href="{{ route('clubs.index') }}" class="rounded-lg border border-zinc-300 dark:border-zinc-600 px-4 py-2 text-sm text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-700">Clear</a>
            @endif
        </form>

        <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            @forelse($clubs as $club)
            <a href="{{ route('clubs.show', $club->id) }}" class="block rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4 hover:shadow-md transition group">
                <div class="flex items-center gap-3 mb-3">
                    @if($club->logo)
                    <img src="{{ $club->logo }}" alt="{{ $club->name }}" class="w-12 h-12 object-contain">
                    @else
                    <div class="w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-lg font-bold text-green-600">
                        {{ strtoupper(substr($club->code ?? $club->name, 0, 3)) }}
                    </div>
                    @endif
                    <div>
                        <div class="font-semibold text-zinc-900 dark:text-white group-hover:text-green-600 transition">{{ $club->name }}</div>
                        <div class="text-xs text-zinc-400">{{ $club->country }}</div>
                    </div>
                </div>
                @if($club->venue['name'] ?? null)
                <div class="text-xs text-zinc-400">🏟 {{ $club->venue['name'] }}</div>
                @endif
                @if($club->founded)
                <div class="text-xs text-zinc-400 mt-1">Est. {{ $club->founded }}</div>
                @endif
            </a>
            @empty
            <div class="col-span-full text-center py-12 text-zinc-400">
                @if($apiConfigured)
                <div class="text-4xl mb-3">⚽</div>
                <p>No clubs found.</p>
                @else
                <div class="text-4xl mb-3">🔌</div>
                <p>Configure your API key to see club data.</p>
                @endif
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if(($pagination['last_page'] ?? 1) > 1)
        <div class="flex items-center justify-center gap-2 py-4">
            @if($pagination['current_page'] > 1)
            <a href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] - 1]) }}" class="rounded-lg border border-zinc-300 dark:border-zinc-600 px-3 py-2 text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700">← Previous</a>
            @endif
            <span class="text-sm text-zinc-500">Page {{ $pagination['current_page'] }} of {{ $pagination['last_page'] }}</span>
            @if($pagination['has_more'] ?? false)
            <a href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] + 1]) }}" class="rounded-lg border border-zinc-300 dark:border-zinc-600 px-3 py-2 text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700">Next →</a>
            @endif
        </div>
        @endif
    </div>
</x-layouts::app>
