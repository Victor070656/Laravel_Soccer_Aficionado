<x-layouts::app :title="__('Matches')">
    <div class="max-w-7xl mx-auto space-y-8 p-2 sm:p-4">
        {{-- Header --}}
        <div class="relative rounded-2xl overflow-hidden bg-gradient-to-r from-green-700 via-emerald-600 to-teal-600 p-6 sm:p-8 text-white shadow-xl">
            <div class="absolute inset-0 opacity-10" style="background-image: url('https://images.unsplash.com/photo-1522778119026-d647f0596c20?w=600&q=30'); background-size: cover; background-position: center;"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold mb-2 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl">⚽</span>
                        Matches
                    </h1>
                    <p class="text-green-100 text-sm sm:text-base">Browse all football matches. Filter by competition, status, or date.</p>
                </div>
                <a href="{{ route('matches.live') }}" class="inline-flex items-center gap-2 rounded-xl bg-red-500/90 backdrop-blur-sm hover:bg-red-600 px-5 py-2.5 text-sm font-bold text-white transition-all shadow-lg shadow-red-500/30 hover:scale-105 border border-red-400/30">
                    <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span></span>
                    Live Matches
                </a>
            </div>
        </div>

        @unless($apiConfigured)
        <div class="rounded-2xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 p-5 flex items-start gap-3">
            <span class="text-2xl">⚠️</span>
            <div>
                <h3 class="font-bold text-amber-800 dark:text-amber-400 text-sm">API Key Not Configured</h3>
                <p class="text-sm text-amber-700 dark:text-amber-400/80 mt-0.5">Add <code class="bg-amber-100 dark:bg-amber-900/40 px-1.5 py-0.5 rounded text-xs font-mono">FOOTBALL_API_KEY</code> to your <code class="bg-amber-100 dark:bg-amber-900/40 px-1.5 py-0.5 rounded text-xs font-mono">.env</code> file to see live match data.</p>
            </div>
        </div>
        @endunless

        {{-- Filters --}}
        <form method="GET" class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 p-5 shadow-sm">
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex-1 min-w-[160px]">
                    <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1.5">Competition</label>
                    <select name="league" class="w-full rounded-xl border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white text-sm p-4 focus:border-green-500 focus:ring-green-500/20">
                        <option value="">All Competitions</option>
                        @foreach($leagues as $league)
                        <option value="{{ $league->id }}" {{ request('league') == $league->id ? 'selected' : '' }}>{{ $league->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[140px]">
                    <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1.5">Status</label>
                    <select name="status" class="w-full rounded-xl border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white text-sm p-4 focus:border-green-500 focus:ring-green-500/20">
                        <option value="">All Statuses</option>
                        @foreach(['scheduled' => 'Scheduled', 'live' => 'Live', 'finished' => 'Finished'] as $value => $label)
                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1.5">Date</label>
                    <input type="date" name="date" value="{{ request('date') }}" class="w-full rounded-xl border-zinc-200 dark:border-zinc-600 p-4 dark:bg-zinc-900/50 dark:text-white text-sm focus:border-green-500 focus:ring-green-500/20">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="rounded-xl bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-2.5 text-sm font-semibold text-white hover:from-green-500 hover:to-emerald-400 transition-all shadow-md shadow-green-600/20 hover:scale-105">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    @if(request()->hasAny(['league', 'status', 'date']))
                    <a href="{{ route('matches.index') }}" class="rounded-xl border border-zinc-200 dark:border-zinc-600 px-4 py-2.5 text-sm text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-all">Clear</a>
                    @endif
                </div>
            </div>
        </form>

        {{-- Banner Ad --}}
        <x-ad-unit placement="banner" />

        {{-- Match List --}}
        <div class="space-y-4">
            @forelse($matches as $match)
            <a href="{{ route('matches.show', $match->id) }}" class="group block rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 p-5 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-0.5">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        @if($match->league['logo'] ?? null)
                        <img loading="lazy" decoding="async" src="{{ $match->league['logo'] }}" alt="" class="h-5 w-5 object-contain">
                        @endif
                        <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400">{{ $match->league['name'] }} · {{ \Carbon\Carbon::parse($match->date)->format('M d, Y H:i') }}</span>
                    </div>
                    @php
                        $statusClasses = match($match->status) {
                            'live', 'half_time' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 border-red-200 dark:border-red-800/50',
                            'finished' => 'bg-zinc-100 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300 border-zinc-200 dark:border-zinc-600',
                            'scheduled' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 border-green-200 dark:border-green-800/50',
                            default => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 border-yellow-200 dark:border-yellow-800/50',
                        };
                    @endphp
                    <span class="text-xs font-medium px-3 py-1 rounded-full border {{ $statusClasses }}">
                        @if(in_array($match->status, ['live', 'half_time']))
                            <span class="relative inline-flex h-1.5 w-1.5 mr-1"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span><span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-red-500"></span></span>
                        @endif
                        {{ ucfirst(str_replace('_', ' ', $match->status)) }}
                        @if($match->elapsed && in_array($match->status, ['live', 'half_time'])) · {{ $match->elapsed }}'@endif
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex-1 flex items-center gap-3">
                        @if($match->home_team['logo'] ?? null)
                        <img loading="lazy" decoding="async" src="{{ $match->home_team['logo'] }}" alt="" class="h-10 w-10 object-contain group-hover:scale-110 transition-transform">
                        @else
                        <div class="h-10 w-10 rounded-full bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center text-lg">⚽</div>
                        @endif
                        <div class="font-bold text-zinc-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">{{ $match->home_team['name'] }}</div>
                    </div>
                    <div class="px-5 text-center">
                        <span class="text-3xl font-bold {{ in_array($match->status, ['live', 'half_time']) ? 'bg-gradient-to-r from-red-500 to-orange-500 bg-clip-text text-transparent' : 'text-zinc-900 dark:text-white' }}">{{ $match->score_display }}</span>
                    </div>
                    <div class="flex-1 flex items-center justify-end gap-3">
                        <div class="font-bold text-zinc-900 dark:text-white text-right group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">{{ $match->away_team['name'] }}</div>
                        @if($match->away_team['logo'] ?? null)
                        <img loading="lazy" decoding="async" src="{{ $match->away_team['logo'] }}" alt="" class="h-10 w-10 object-contain group-hover:scale-110 transition-transform">
                        @else
                        <div class="h-10 w-10 rounded-full bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center text-lg">⚽</div>
                        @endif
                    </div>
                </div>
                @if($match->venue || ($match->league['round'] ?? null))
                <div class="flex items-center gap-3 mt-3 pt-3 border-t border-zinc-100 dark:border-zinc-700/50 text-xs text-zinc-400">
                    @if($match->venue)<span class="flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>{{ $match->venue }}</span>@endif
                    @if($match->league['round'] ?? null)<span>{{ $match->league['round'] }}</span>@endif
                </div>
                @endif
            </a>
            @empty
            <div class="rounded-2xl border-2 border-dashed border-zinc-200 dark:border-zinc-700 p-16 text-center">
                @if($apiConfigured)
                <div class="text-5xl mb-4">⚽</div>
                <h3 class="font-bold text-zinc-900 dark:text-white mb-2">No matches found</h3>
                <p class="text-sm text-zinc-500">Try adjusting your filters to find matches.</p>
                @else
                <div class="text-5xl mb-4">🔌</div>
                <h3 class="font-bold text-zinc-900 dark:text-white mb-2">API Not Configured</h3>
                <p class="text-sm text-zinc-500">Configure your API key to see live match data.</p>
                @endif
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if(($pagination['last_page'] ?? 1) > 1)
        <div class="flex items-center justify-center gap-3 py-4">
            @if($pagination['current_page'] > 1)
            <a href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] - 1]) }}" class="inline-flex items-center gap-2 rounded-xl border border-zinc-200 dark:border-zinc-600 px-5 py-2.5 text-sm font-medium hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-all">← Previous</a>
            @endif
            <span class="text-sm text-zinc-500 bg-zinc-100 dark:bg-zinc-700 px-4 py-2 rounded-lg">Page {{ $pagination['current_page'] }} of {{ $pagination['last_page'] }}</span>
            @if($pagination['has_more'] ?? false)
            <a href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] + 1]) }}" class="inline-flex items-center gap-2 rounded-xl border border-zinc-200 dark:border-zinc-600 px-5 py-2.5 text-sm font-medium hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-all">Next →</a>
            @endif
        </div>
        @endif
    </div>
</x-layouts::app>
