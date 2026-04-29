<x-layouts::app :title="__('Matches')">
    <div class="max-w-7xl mx-auto space-y-8 p-2 sm:p-4">
        {{-- Header --}}
        <div class="relative rounded-2xl overflow-hidden bg-gradient-to-r from-primary via-primary/80 to-primary/60 p-6 sm:p-8 text-on-primary shadow-xl">
            <div class="absolute inset-0 opacity-10" style="background-image: url('https://images.unsplash.com/photo-1522778119026-d647f0596c20?w=600&q=30'); background-size: cover; background-position: center;"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold mb-2 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl">⚽</span>
                        Matches
                    </h1>
                    <p class="text-on-primary/70 text-sm sm:text-base">Browse all football matches. Filter by competition, status, or date.</p>
                </div>
                <a href="{{ route('matches.live') }}" class="inline-flex items-center gap-2 rounded-xl bg-secondary/90 backdrop-blur-sm hover:bg-secondary px-5 py-2.5 text-sm font-bold text-on-secondary transition-all shadow-lg shadow-secondary/30 hover:scale-105 border border-secondary/50 glass-edge">
                    <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-current opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-current"></span></span>
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
        <form method="GET" class="rounded-2xl border border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container p-5 shadow-sm glass-edge">
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex-1 min-w-[160px]">
                    <label class="block text-xs font-medium text-on-surface-variant mb-1.5">Competition</label>
                    <select name="league" class="w-full rounded-xl border-outline-variant/20 dark:border-outline-variant/30 dark:bg-surface-container-high dark:text-on-surface text-sm p-4 focus:border-primary focus:ring-primary/20">
                        <option value="">All Competitions</option>
                        @foreach($leagues as $league)
                        <option value="{{ $league->id }}" {{ request('league') == $league->id ? 'selected' : '' }}>{{ $league->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[140px]">
                    <label class="block text-xs font-medium text-on-surface-variant mb-1.5">Status</label>
                    <select name="status" class="w-full rounded-xl border-outline-variant/20 dark:border-outline-variant/30 dark:bg-surface-container-high dark:text-on-surface text-sm p-4 focus:border-primary focus:ring-primary/20">
                        <option value="">All Statuses</option>
                        @foreach(['scheduled' => 'Scheduled', 'live' => 'Live', 'finished' => 'Finished'] as $value => $label)
                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-xs font-medium text-on-surface-variant mb-1.5">Date</label>
                    <input type="date" name="date" value="{{ request('date') }}" class="w-full rounded-xl border-outline-variant/20 dark:border-outline-variant/30 p-4 dark:bg-surface-container-high dark:text-on-surface text-sm focus:border-primary focus:ring-primary/20">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="rounded-xl bg-gradient-to-r from-primary to-primary/80 px-6 py-2.5 text-sm font-semibold text-on-primary hover:from-primary/90 hover:to-primary/70 transition-all shadow-md shadow-primary/20 hover:scale-105">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    @if(request()->hasAny(['league', 'status', 'date']))
                    <a href="{{ route('matches.index') }}" class="rounded-xl border border-outline-variant/20 dark:border-outline-variant/30 px-4 py-2.5 text-sm text-on-surface-variant hover:bg-surface-container-high dark:hover:bg-surface-container-high transition-all">Clear</a>
                    @endif
                </div>
            </div>
        </form>

        {{-- Banner Ad --}}
        <x-ad-unit placement="banner" />

        {{-- Match List --}}
        <div class="space-y-4">
            @forelse($matches as $match)
            <a href="{{ route('matches.show', $match->id) }}" class="group block rounded-2xl border border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container p-5 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-0.5 glass-edge">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        @if($match->league['logo'] ?? null)
                        <img loading="lazy" decoding="async" src="{{ $match->league['logo'] }}" alt="" class="h-5 w-5 object-contain">
                        @endif
                        <span class="text-xs font-medium text-on-surface-variant">{{ $match->league['name'] }} · {{ \Carbon\Carbon::parse($match->date)->format('M d, Y H:i') }}</span>
                    </div>
                    @php
                        $statusClasses = match($match->status) {
                            'live', 'half_time' => 'bg-secondary/10 text-secondary dark:bg-secondary/20 dark:text-secondary border-secondary/30 dark:border-secondary/40',
                            'finished' => 'bg-outline-variant/10 text-on-surface-variant dark:bg-outline-variant/20 dark:text-on-surface-variant border-outline-variant/30 dark:border-outline-variant/40',
                            'scheduled' => 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary border-primary/30 dark:border-primary/40',
                            default => 'bg-tertiary/10 text-tertiary dark:bg-tertiary/20 dark:text-tertiary border-tertiary/30 dark:border-tertiary/40',
                        };
                    @endphp
                    <span class="text-xs font-medium px-3 py-1 rounded-full border {{ $statusClasses }}">
                        @if(in_array($match->status, ['live', 'half_time']))
                            <span class="relative inline-flex h-1.5 w-1.5 mr-1"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span><span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-secondary"></span></span>
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
                        <div class="h-10 w-10 rounded-full bg-surface-container-high dark:bg-surface-container-high flex items-center justify-center text-lg">⚽</div>
                        @endif
                        <div class="font-bold text-on-surface group-hover:text-primary dark:group-hover:text-primary transition-colors">{{ $match->home_team['name'] }}</div>
                    </div>
                    <div class="px-5 text-center">
                        <span class="text-3xl font-bold {{ in_array($match->status, ['live', 'half_time']) ? 'bg-gradient-to-r from-secondary to-tertiary bg-clip-text text-transparent' : 'text-on-surface' }}">{{ $match->score_display }}</span>
                    </div>
                    <div class="flex-1 flex items-center justify-end gap-3">
                        <div class="font-bold text-on-surface text-right group-hover:text-primary dark:group-hover:text-primary transition-colors">{{ $match->away_team['name'] }}</div>
                        @if($match->away_team['logo'] ?? null)
                        <img loading="lazy" decoding="async" src="{{ $match->away_team['logo'] }}" alt="" class="h-10 w-10 object-contain group-hover:scale-110 transition-transform">
                        @else
                        <div class="h-10 w-10 rounded-full bg-surface-container-high dark:bg-surface-container-high flex items-center justify-center text-lg">⚽</div>
                        @endif
                    </div>
                </div>
                @if($match->venue || ($match->league['round'] ?? null))
                <div class="flex items-center gap-3 mt-3 pt-3 border-t border-outline-variant/20 dark:border-outline-variant/30 text-xs text-on-surface-variant">
                    @if($match->venue)<span class="flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>{{ $match->venue }}</span>@endif
                    @if($match->league['round'] ?? null)<span>{{ $match->league['round'] }}</span>@endif
                </div>
                @endif
            </a>
            @empty
            <div class="rounded-2xl border-2 border-dashed border-outline-variant/30 dark:border-outline-variant/40 p-16 text-center">
                @if($apiConfigured)
                <div class="text-5xl mb-4">⚽</div>
                <h3 class="font-bold text-on-surface mb-2">No matches found</h3>
                <p class="text-sm text-on-surface-variant">Try adjusting your filters to find matches.</p>
                @else
                <div class="text-5xl mb-4">🔌</div>
                <h3 class="font-bold text-on-surface mb-2">API Not Configured</h3>
                <p class="text-sm text-on-surface-variant">Configure your API key to see live match data.</p>
                @endif
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if(($pagination['last_page'] ?? 1) > 1)
        <div class="flex items-center justify-center gap-3 py-4">
            @if($pagination['current_page'] > 1)
            <a href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] - 1]) }}" class="inline-flex items-center gap-2 rounded-xl border border-outline-variant/20 dark:border-outline-variant/30 px-5 py-2.5 text-sm font-medium hover:bg-surface-container-high dark:hover:bg-surface-container-high transition-all text-on-surface">← Previous</a>
            @endif
            <span class="text-sm text-on-surface-variant bg-surface-container-high dark:bg-surface-container-high px-4 py-2 rounded-lg">Page {{ $pagination['current_page'] }} of {{ $pagination['last_page'] }}</span>
            @if($pagination['has_more'] ?? false)
            <a href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] + 1]) }}" class="inline-flex items-center gap-2 rounded-xl border border-outline-variant/20 dark:border-outline-variant/30 px-5 py-2.5 text-sm font-medium hover:bg-surface-container-high dark:hover:bg-surface-container-high transition-all text-on-surface">Next →</a>
            @endif
        </div>
        @endif
    </div>
</x-layouts::app>
