<x-layouts::app :title="__('Competitions')">
    <div class="max-w-7xl mx-auto space-y-8 p-2 sm:p-4">
        {{-- Header --}}
        <div class="relative rounded-2xl overflow-hidden bg-gradient-to-r from-amber-600 via-yellow-600 to-orange-600 p-6 sm:p-8 text-white shadow-xl">
            <div class="absolute inset-0 opacity-10" style="background-image: url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=600&q=30'); background-size: cover; background-position: center;"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/3 -translate-x-1/4"></div>
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold mb-2 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl">🏆</span>
                        Competitions
                    </h1>
                    <p class="text-amber-100 text-sm sm:text-base">Explore top leagues and tournaments from around the world.</p>
                </div>
                <span class="inline-flex items-center gap-2 rounded-xl bg-white/15 backdrop-blur-sm px-4 py-2 text-sm font-medium border border-white/20">
                    📅 Season {{ $seasonDisplay }}
                </span>
            </div>
        </div>

        @unless($apiConfigured)
        <div class="rounded-2xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 p-5 flex items-start gap-3">
            <span class="text-2xl">⚠️</span>
            <div>
                <h3 class="font-bold text-amber-800 dark:text-amber-400 text-sm">API Key Not Configured</h3>
                <p class="text-sm text-amber-700 dark:text-amber-400/80 mt-0.5">Add <code class="bg-amber-100 dark:bg-amber-900/40 px-1.5 py-0.5 rounded text-xs font-mono">FOOTBALL_API_KEY</code> to your <code class="bg-amber-100 dark:bg-amber-900/40 px-1.5 py-0.5 rounded text-xs font-mono">.env</code> file to see competition data.</p>
            </div>
        </div>
        @endunless

        {{-- Competition Cards --}}
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($competitions as $competition)
            <a href="{{ route('competitions.show', $competition->id) }}" class="group block rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm hover:shadow-lg hover:shadow-amber-500/10 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div class="p-5">
                    <div class="flex items-center gap-3 mb-3">
                        @if($competition->logo)
                        <div class="w-12 h-12 rounded-xl bg-zinc-50 dark:bg-zinc-700/50 flex items-center justify-center p-1.5 group-hover:scale-110 transition-transform duration-300">
                            <img loading="lazy" decoding="async" src="{{ $competition->logo }}" alt="{{ $competition->name }}" class="w-8 h-8 object-contain">
                        </div>
                        @else
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-100 to-yellow-100 dark:from-amber-900/30 dark:to-yellow-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400 font-bold text-sm group-hover:scale-110 transition-transform duration-300">
                            {{ strtoupper(substr($competition->name, 0, 2)) }}
                        </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-zinc-900 dark:text-white group-hover:text-amber-600 transition text-sm">{{ $competition->name }}</div>
                            <div class="flex items-center gap-1.5 text-xs text-zinc-400 dark:text-zinc-500 mt-0.5">
                                @if($competition->country_flag ?? null)
                                <img loading="lazy" decoding="async" src="{{ $competition->country_flag }}" alt="" class="h-3 w-4 object-contain">
                                @endif
                                {{ ucfirst($competition->type) }} · {{ $competition->country ?? 'International' }}
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-zinc-100 dark:border-zinc-700/40">
                        <span class="text-xs text-zinc-400 dark:text-zinc-500">Season: {{ $competition->season }}</span>
                        @if($competition->season_start && $competition->season_end)
                        <span class="text-xs text-zinc-400 dark:text-zinc-500">{{ $competition->season_start }} → {{ $competition->season_end }}</span>
                        @endif
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full">
                <div class="rounded-2xl border-2 border-dashed border-zinc-200 dark:border-zinc-700 p-12 text-center">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                        @if($apiConfigured)
                        <span class="text-4xl">🏆</span>
                        @else
                        <span class="text-4xl">🔌</span>
                        @endif
                    </div>
                    <h2 class="text-xl font-bold text-zinc-700 dark:text-zinc-300 mb-2">
                        @if($apiConfigured)
                        No Competitions Found
                        @else
                        API Not Configured
                        @endif
                    </h2>
                    <p class="text-sm text-zinc-400 dark:text-zinc-500 max-w-md mx-auto">
                        @if($apiConfigured)
                        No competitions are available at the moment.
                        @else
                        Configure your API key to see competition data.
                        @endif
                    </p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</x-layouts::app>
