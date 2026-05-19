<x-layouts::app :title="__('Clubs')">
    <div class="max-w-7xl mx-auto space-y-8 p-2 sm:p-4">
        {{-- Header --}}
        <div class="relative rounded-2xl overflow-hidden bg-gradient-stadium-primary p-6 sm:p-8 text-on-primary shadow-xl shadow-primary/20 glow-primary-lg">
            <div class="absolute inset-0 opacity-10" style="background-image: url('https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?w=600&q=30'); background-size: cover; background-position: center;"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/3 -translate-x-1/4"></div>
            <div class="relative z-10">
                <h1 class="text-h2-mobile sm:text-h1 font-bold mb-2 flex items-center gap-3 text-on-primary uppercase tracking-wide">
                    <span class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl glow-primary">⚽</span>
                    Clubs
                </h1>
                <p class="text-on-primary/70 text-sm sm:text-base font-medium">Explore football clubs from around the world.</p>
            </div>
        </div>

        @unless($apiConfigured)
        <div class="rounded-2xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 p-5 flex items-start gap-3">
            <span class="text-2xl">⚠️</span>
            <div>
                <h3 class="font-bold text-amber-800 dark:text-amber-400 text-sm">API Key Not Configured</h3>
                <p class="text-sm text-amber-700 dark:text-amber-400/80 mt-0.5">Add <code class="bg-amber-100 dark:bg-amber-900/40 px-1.5 py-0.5 rounded text-xs font-mono">FOOTBALL_API_KEY</code> to your <code class="bg-amber-100 dark:bg-amber-900/40 px-1.5 py-0.5 rounded text-xs font-mono">.env</code> file to see club data.</p>
            </div>
        </div>
        @endunless

        {{-- Search --}}
        <form method="GET" class="card rounded-2xl border border-primary/20 bg-gradient-to-b from-surface-container/80 to-surface-container/40 p-5 shadow-sm glass-premium">
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-xs font-bold text-on-surface-variant mb-1.5 uppercase tracking-wide">Club Name</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search clubs..." class="w-full p-4 rounded-xl border border-primary/10 bg-surface/60 dark:bg-surface/60 dark:text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/30 text-sm transition-all">
                </div>
                <div class="w-40">
                    <label class="block text-xs font-bold text-on-surface-variant mb-1.5 uppercase tracking-wide">Country</label>
                    <input type="text" name="country" value="{{ request('country') }}" placeholder="e.g. England" class="w-full p-4 rounded-xl border border-primary/10 bg-surface/60 dark:bg-surface/60 dark:text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/30 text-sm transition-all">
                </div>
                <button type="submit" class="rounded-xl bg-gradient-to-r from-primary to-primary/80 px-6 py-3 text-sm font-bold text-on-primary hover:from-primary/90 hover:to-primary/70 transition-all shadow-md shadow-primary/20 hover:scale-105 glow-primary uppercase tracking-wide">
                    Search
                </button>
                @if(request()->hasAny(['search', 'country']))
                <a href="{{ route('clubs.index') }}" class="rounded-xl border border-primary/20 px-6 py-3 text-sm font-bold text-on-surface-variant hover:bg-primary/10 dark:hover:bg-primary/10 transition-all uppercase tracking-wide">Clear</a>
                @endif
            </div>
        </form>

        {{-- Banner Ad --}}
        <x-ad-unit placement="banner" />

        {{-- Club Cards --}}
        <div class="grid gap-5 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            @forelse($clubs as $club)
            <a href="{{ route('clubs.show', $club->id) }}" class="group card card-club block rounded-2xl border border-primary/15 bg-gradient-to-b from-surface-container/80 to-surface-container/40 shadow-sm hover:shadow-card-lg transition-all duration-300 hover:-translate-y-1 overflow-hidden glass-premium hover:glow-primary">
                <div class="p-5">
                    <div class="flex items-center gap-3 mb-3">
                        @if($club->logo)
                        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center p-1.5 group-hover:scale-110 transition-transform duration-300 glow-primary">
                            <img loading="lazy" decoding="async" src="{{ $club->logo }}" alt="{{ $club->name }}" class="w-10 h-10 object-contain">
                        </div>
                        @else
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-primary/30 to-primary/20 flex items-center justify-center text-lg font-bold text-primary group-hover:scale-110 transition-transform duration-300 glow-primary">
                            {{ strtoupper(substr($club->code ?? $club->name, 0, 3)) }}
                        </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-on-surface group-hover:text-primary transition text-sm truncate">{{ $club->name }}</div>
                            <div class="text-xs text-on-surface-variant mt-0.5 font-semibold uppercase tracking-wide">{{ $club->country }}</div>
                        </div>
                    </div>
                    @if($club->venue['name'] ?? null)
                    <div class="flex items-center gap-1.5 text-xs text-on-surface-variant">
                        <span>🏟</span>
                        <span class="truncate">{{ $club->venue['name'] }}</span>
                    </div>
                    @endif
                    @if($club->founded)
                    <div class="flex items-center gap-1.5 text-xs text-on-surface-variant mt-1">
                        <span>📅</span>
                        <span>Est. {{ $club->founded }}</span>
                    </div>
                    @endif
                </div>
            </a>
            @empty
            <div class="col-span-full">
                <div class="rounded-2xl border-2 border-dashed border-zinc-200 dark:border-zinc-700 p-12 text-center">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                        @if($apiConfigured)
                        <span class="text-4xl">⚽</span>
                        @else
                        <span class="text-4xl">🔌</span>
                        @endif
                    </div>
                    <h2 class="text-xl font-bold text-zinc-700 dark:text-zinc-300 mb-2">
                        @if($apiConfigured)
                        No Clubs Found
                        @else
                        API Not Configured
                        @endif
                    </h2>
                    <p class="text-sm text-zinc-400 dark:text-zinc-500 max-w-md mx-auto">
                        @if($apiConfigured)
                        Try adjusting your search criteria.
                        @else
                        Configure your API key to see club data.
                        @endif
                    </p>
                </div>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if(($pagination['last_page'] ?? 1) > 1)
        <div class="flex items-center justify-center gap-3 py-4">
            @if($pagination['current_page'] > 1)
            <a href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] - 1]) }}" class="rounded-xl border border-zinc-200 dark:border-zinc-600 px-5 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Previous
            </a>
            @endif
            <span class="text-sm text-zinc-500 dark:text-zinc-400 font-medium">Page {{ $pagination['current_page'] }} of {{ $pagination['last_page'] }}</span>
            @if($pagination['has_more'] ?? false)
            <a href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] + 1]) }}" class="rounded-xl border border-zinc-200 dark:border-zinc-600 px-5 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition flex items-center gap-1">
                Next
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            @endif
        </div>
        @endif
    </div>
</x-layouts::app>
