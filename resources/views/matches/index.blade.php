<x-layouts::app :title="__('Matches')">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Matches</h1>
            <a href="{{ route('matches.live') }}" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition">
                <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-300 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span></span>
                Live
            </a>
        </div>

        @unless($apiConfigured)
        <div class="rounded-lg bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 p-4 text-sm text-yellow-700 dark:text-yellow-400">
            ⚠️ Football API key is not configured. Add <code>FOOTBALL_API_KEY</code> to your <code>.env</code> file to see live match data.
        </div>
        @endunless

        {{-- Filters --}}
        <form method="GET" class="flex flex-wrap gap-3">
            <select name="league" class="rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm">
                <option value="">All Competitions</option>
                @foreach($leagues as $league)
                <option value="{{ $league->id }}" {{ request('league') == $league->id ? 'selected' : '' }}>{{ $league->name }}</option>
                @endforeach
            </select>
            <select name="status" class="rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm">
                <option value="">All Statuses</option>
                @foreach(['scheduled' => 'Scheduled', 'live' => 'Live', 'finished' => 'Finished'] as $value => $label)
                <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <input type="date" name="date" value="{{ request('date') }}" class="rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm">
            <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-700">Filter</button>
            @if(request()->hasAny(['league', 'status', 'date']))
            <a href="{{ route('matches.index') }}" class="rounded-lg border border-zinc-300 dark:border-zinc-600 px-4 py-2 text-sm text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-700">Clear</a>
            @endif
        </form>

        {{-- Match List --}}
        <div class="space-y-3">
            @forelse($matches as $match)
            <a href="{{ route('matches.show', $match->id) }}" class="block rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        @if($match->league['logo'] ?? null)
                        <img src="{{ $match->league['logo'] }}" alt="" class="h-4 w-4 object-contain">
                        @endif
                        <span class="text-xs text-zinc-400">{{ $match->league['name'] }} · {{ \Carbon\Carbon::parse($match->date)->format('M d, Y H:i') }}</span>
                    </div>
                    @php
                        $statusClasses = match($match->status) {
                            'live', 'half_time' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                            'finished' => 'bg-zinc-100 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300',
                            'scheduled' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                            default => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                        };
                    @endphp
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $statusClasses }}">
                        {{ ucfirst(str_replace('_', ' ', $match->status)) }}
                        @if($match->elapsed && in_array($match->status, ['live', 'half_time']))
                        <span class="font-mono">{{ $match->elapsed }}'</span>
                        @endif
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex-1 flex items-center gap-3">
                        @if($match->home_team['logo'] ?? null)
                        <img src="{{ $match->home_team['logo'] }}" alt="" class="h-8 w-8 object-contain">
                        @endif
                        <div class="font-semibold text-zinc-900 dark:text-white">{{ $match->home_team['name'] }}</div>
                    </div>
                    <div class="px-4 text-center">
                        <span class="text-2xl font-bold {{ in_array($match->status, ['live', 'half_time']) ? 'text-red-600' : 'text-zinc-900 dark:text-white' }}">{{ $match->score_display }}</span>
                    </div>
                    <div class="flex-1 flex items-center justify-end gap-3">
                        <div class="font-semibold text-zinc-900 dark:text-white text-right">{{ $match->away_team['name'] }}</div>
                        @if($match->away_team['logo'] ?? null)
                        <img src="{{ $match->away_team['logo'] }}" alt="" class="h-8 w-8 object-contain">
                        @endif
                    </div>
                </div>
                @if($match->venue)
                <div class="text-xs text-zinc-400 mt-2">📍 {{ $match->venue }}</div>
                @endif
                @if($match->league['round'] ?? null)
                <div class="text-xs text-zinc-400 mt-1">{{ $match->league['round'] }}</div>
                @endif
            </a>
            @empty
            <div class="text-center py-12 text-zinc-400">
                @if($apiConfigured)
                <div class="text-4xl mb-3">⚽</div>
                <p>No matches found for the selected filters.</p>
                @else
                <div class="text-4xl mb-3">🔌</div>
                <p>Configure your API key to see live match data.</p>
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
