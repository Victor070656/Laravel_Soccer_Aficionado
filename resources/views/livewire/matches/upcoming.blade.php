<div class="rounded-2xl border border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container overflow-hidden shadow-sm glass-edge">
    <div class="px-5 py-4 bg-gradient-to-r from-primary/10 to-primary/5 dark:from-primary/15 dark:to-primary/10 border-b border-outline-variant/20 dark:border-outline-variant/30">
        <h3 class="font-bold text-sm text-on-surface flex items-center gap-2">
            <span class="text-base">📅</span> Upcoming Matches
        </h3>
    </div>
    <div class="p-3">
        @forelse($upcomingMatches as $match)
        <a href="{{ route('matches.show', $match->id) }}" class="block p-3 rounded-xl hover:bg-primary/5 dark:hover:bg-primary/10 transition-all duration-200 group/match">
            <div class="flex items-center gap-1.5 text-xs text-on-surface-variant mb-1.5">
                @if($match->league['logo'] ?? null)
                <img loading="lazy" decoding="async" src="{{ $match->league['logo'] }}" alt="" class="h-3.5 w-3.5 object-contain">
                @endif
                <span>{{ \Carbon\Carbon::parse($match->date)->format('M d, H:i') }}</span>
                <span class="text-outline-variant/40 dark:text-outline-variant/30">·</span>
                <span>{{ $match->league['name'] }}</span>
            </div>
            <div class="flex justify-between items-center text-sm font-medium text-on-surface">
                <div class="flex items-center gap-1.5">
                    @if($match->home_team['logo'] ?? null)
                    <img loading="lazy" decoding="async" src="{{ $match->home_team['logo'] }}" alt="" class="h-5 w-5 object-contain">
                    @endif
                    <span class="group-hover/match:text-primary transition-colors">{{ $match->home_team['name'] }}</span>
                </div>
                <span class="text-xs font-normal text-on-surface-variant bg-surface-container-high dark:bg-surface-container-high px-2 py-0.5 rounded-md">vs</span>
                <div class="flex items-center gap-1.5">
                    <span class="group-hover/match:text-primary transition-colors">{{ $match->away_team['name'] }}</span>
                    @if($match->away_team['logo'] ?? null)
                    <img loading="lazy" decoding="async" src="{{ $match->away_team['logo'] }}" alt="" class="h-5 w-5 object-contain">
                    @endif
                </div>
            </div>
        </a>
        @empty
        <p class="text-sm text-on-surface-variant p-3 text-center">No upcoming matches.</p>
        @endforelse
    </div>
</div>
