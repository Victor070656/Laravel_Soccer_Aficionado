<x-layouts::app :title="__('Leagues')">
    <div class="min-h-screen bg-surface py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="glass-card rounded-xl p-6 relative overflow-hidden">
                <div class="absolute -top-20 -right-20 h-40 w-40 rounded-full bg-primary-container/10 blur-3xl"></div>
                <div class="relative z-10 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-headline-lg text-on-surface flex items-center gap-3">
                            <span class="w-12 h-12 rounded-xl bg-primary-container/20 flex items-center justify-center text-2xl">🏆</span>
                            Leagues
                        </h1>
                        <p class="mt-2 text-body-md text-on-surface-variant">Explore top leagues and tournaments from around the world.</p>
                    </div>
                    <span class="inline-flex items-center gap-2 rounded-xl bg-surface-container-high px-4 py-2 text-sm font-medium text-on-surface-variant">
                        📅 Season {{ $seasonDisplay }}
                    </span>
                </div>
            </div>

            @unless($apiConfigured)
                <div class="glass-card rounded-xl p-5">
                    <p class="text-sm text-on-surface-variant">API key not configured. Add <code class="rounded bg-surface-container-high px-1.5 py-0.5">FOOTBALL_API_KEY</code> to your .env file to see league data.</p>
                </div>
            @endunless

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($competitions as $competition)
                    <a href="{{ route('competitions.show', $competition->id) }}" class="glass-card rounded-xl p-5 hover:bg-surface-container/50 transition-all hover:scale-[1.02] group">
                        <div class="flex items-center gap-3">
                            @if($competition->logo ?? null)
                                <div class="h-12 w-12 rounded-xl bg-surface-container-high flex items-center justify-center overflow-hidden">
                                    <img loading="lazy" decoding="async" src="{{ $competition->logo }}" alt="{{ $competition->name }}" class="h-8 w-8 object-contain">
                                </div>
                            @else
                                <div class="h-12 w-12 rounded-xl bg-primary-container/15 flex items-center justify-center text-xs font-black text-primary-container">
                                    {{ strtoupper(substr($competition->name, 0, 2)) }}
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <h3 class="text-label-bold text-on-surface group-hover:text-primary-container transition-colors truncate">{{ $competition->name }}</h3>
                                <p class="mt-1 text-label-sm text-on-surface-variant">{{ ucfirst($competition->type) }} · {{ $competition->country ?? 'International' }}</p>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between border-t border-outline-variant/20 pt-3 text-xs text-on-surface-variant">
                            <span>Season: {{ $competition->season }}</span>
                            @if($competition->season_start && $competition->season_end)
                                <span>{{ $competition->season_start }} → {{ $competition->season_end }}</span>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="col-span-full glass-card rounded-xl p-8 text-center">
                        <div class="text-4xl mb-4">🏆</div>
                        <h2 class="text-headline-md text-on-surface mb-2">{{ $apiConfigured ? 'No leagues found' : 'API not configured' }}</h2>
                        <p class="text-body-md text-on-surface-variant">{{ $apiConfigured ? 'No leagues are available right now.' : 'Configure your API key to load league data.' }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts::app>
