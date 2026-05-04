<div>
@if($liveMatches->isNotEmpty())
<div class="rounded-2xl bg-gradient-to-r from-secondary/10 to-secondary/5 dark:from-secondary/20 dark:to-secondary/10 p-5 sm:p-6 border border-secondary/30 dark:border-secondary/20 shadow-lg shadow-secondary/5">
    <div class="flex items-center gap-3 mb-4">
        <div class="flex items-center gap-2 bg-secondary text-on-secondary px-3 py-1.5 rounded-full text-sm font-bold shadow-lg shadow-secondary/30">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-current opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-current"></span>
            </span>
            LIVE
        </div>
        <h2 class="text-lg font-bold text-secondary">Live Matches</h2>
        <a href="{{ route('matches.live') }}" class="ml-auto text-sm text-secondary hover:text-secondary/80 font-medium">View All →</a>
    </div>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($liveMatches as $match)
        <a href="{{ route('matches.show', $match->id) }}" class="group block rounded-xl bg-surface-container dark:bg-surface-container p-4 shadow-md hover:shadow-xl transition-all duration-300 border border-outline-variant/20 dark:border-outline-variant/30 hover:-translate-y-1 glass-edge">
            <div class="flex items-center gap-1.5 text-xs text-on-surface-variant mb-2">
                @if($match->league['logo'] ?? null)
                <img loading="lazy" decoding="async" src="{{ $match->league['logo'] }}" alt="" class="h-4 w-4 object-contain">
                @endif
                <span class="font-medium">{{ $match->league['name'] }}</span>
            </div>
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-2 flex-1 min-w-0">
                    @if($match->home_team['logo'] ?? null)
                    <img loading="lazy" decoding="async" src="{{ $match->home_team['logo'] }}" alt="" class="h-6 w-6 object-contain flex-shrink-0">
                    @endif
                    <span class="font-semibold text-sm truncate">{{ $match->home_team['name'] }}</span>
                </div>
                <div class="flex-shrink-0 bg-gradient-to-r from-primary to-primary/70 text-on-primary font-bold text-lg px-3 py-1 rounded-lg shadow-sm">{{ $match->score_display }}</div>
                <div class="flex items-center gap-2 flex-1 min-w-0 justify-end">
                    <span class="font-semibold text-sm truncate">{{ $match->away_team['name'] }}</span>
                    @if($match->away_team['logo'] ?? null)
                    <img loading="lazy" decoding="async" src="{{ $match->away_team['logo'] }}" alt="" class="h-6 w-6 object-contain flex-shrink-0">
                    @endif
                </div>
            </div>
            <div class="text-center mt-2">
                <span class="inline-flex items-center gap-1.5 text-xs text-secondary font-medium bg-secondary/10 dark:bg-secondary/20 px-2 py-0.5 rounded-full">
                    <span class="relative flex h-1.5 w-1.5"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span><span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-secondary"></span></span>
                    {{ ucfirst(str_replace('_', ' ', $match->status)) }}@if($match->elapsed) · {{ $match->elapsed }}'@endif
                </span>
            </div>

            {{-- Events Ticker --}}
            @if(isset($match->events) && $match->events->count())
            <div class="mt-4 pt-3 border-t border-outline-variant/20 dark:border-outline-variant/30 space-y-1.5">
                @foreach($match->events->take(2) as $event)
                <div class="flex items-center gap-2 text-[10px] text-on-surface-variant">
                    <span class="font-mono text-primary w-6">{{ $event->time }}'</span>
                    <span>{{ $event->icon }}</span>
                    <span class="truncate font-medium">{{ $event->player['name'] }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </a>
        @endforeach
    </div>
</div>
@endif
</div>
