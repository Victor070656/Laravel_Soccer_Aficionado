<x-layouts::app :title="$match->home_team['name'] . ' vs ' . $match->away_team['name']">
    <div class="space-y-6">
        {{-- Match Header --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
            <div class="text-center text-sm text-zinc-400 mb-4">
                @if($match->league['logo'] ?? null)
                <img src="{{ $match->league['logo'] }}" alt="" class="h-5 w-5 inline-block mr-1">
                @endif
                {{ $match->league['name'] }} · {{ \Carbon\Carbon::parse($match->date)->format('l, M d, Y · H:i') }}
                @if($match->venue) · 📍 {{ $match->venue }} @endif
                @if($match->league['round'] ?? null)
                <br>{{ $match->league['round'] }}
                @endif
            </div>
            <div class="flex items-center justify-center gap-8">
                <div class="text-center flex-1">
                    @if($match->home_team['logo'] ?? null)
                    <img src="{{ $match->home_team['logo'] }}" alt="{{ $match->home_team['name'] }}" class="h-16 w-16 object-contain mx-auto mb-2">
                    @endif
                    <div class="text-xl font-bold text-zinc-900 dark:text-white">{{ $match->home_team['name'] }}</div>
                    <div class="text-xs text-zinc-400">Home</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold {{ in_array($match->status, ['live', 'half_time']) ? 'text-red-600' : 'text-zinc-900 dark:text-white' }}">
                        {{ $match->score_display }}
                    </div>
                    @php
                        $statusClasses = match($match->status) {
                            'live', 'half_time' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                            'finished' => 'bg-zinc-100 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300',
                            default => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                        };
                    @endphp
                    <span class="text-xs px-2 py-0.5 rounded-full mt-2 inline-block {{ $statusClasses }}">
                        {{ $match->status_long }}
                        @if($match->elapsed && in_array($match->status, ['live', 'half_time']))
                        <span class="font-mono">{{ $match->elapsed }}'</span>
                        @endif
                    </span>
                </div>
                <div class="text-center flex-1">
                    @if($match->away_team['logo'] ?? null)
                    <img src="{{ $match->away_team['logo'] }}" alt="{{ $match->away_team['name'] }}" class="h-16 w-16 object-contain mx-auto mb-2">
                    @endif
                    <div class="text-xl font-bold text-zinc-900 dark:text-white">{{ $match->away_team['name'] }}</div>
                    <div class="text-xs text-zinc-400">Away</div>
                </div>
            </div>
            {{-- Score details --}}
            @if($match->status === 'finished' && $match->ht_score['home'] !== null)
            <div class="text-center text-xs text-zinc-400 mt-3">HT: {{ $match->ht_score['home'] }} - {{ $match->ht_score['away'] }}</div>
            @endif
            @if($match->et_score['home'] !== null)
            <div class="text-center text-xs text-zinc-400 mt-1">ET: {{ $match->et_score['home'] }} - {{ $match->et_score['away'] }}</div>
            @endif
            @if($match->penalty_score['home'] !== null)
            <div class="text-center text-xs text-zinc-400 mt-1">PEN: {{ $match->penalty_score['home'] }} - {{ $match->penalty_score['away'] }}</div>
            @endif
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            {{-- Match Events --}}
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                <h3 class="font-bold text-zinc-900 dark:text-white mb-4">Match Events</h3>
                @forelse($events as $event)
                <div class="flex items-center gap-3 py-2 border-b border-zinc-100 dark:border-zinc-700 last:border-0">
                    <span class="text-xs font-mono text-zinc-400 w-12">{{ $event->time }}</span>
                    <span>{{ $event->icon }}</span>
                    <div class="flex-1">
                        <span class="text-sm text-zinc-800 dark:text-zinc-200">{{ $event->player['name'] }}</span>
                        @if($event->assist['name'] ?? null)
                        <span class="text-xs text-zinc-400">({{ $event->assist['name'] }})</span>
                        @endif
                        @if($event->detail)
                        <div class="text-xs text-zinc-400">{{ $event->detail }}</div>
                        @endif
                    </div>
                    <span class="text-xs text-zinc-400">{{ $event->team['name'] }}</span>
                </div>
                @empty
                <p class="text-sm text-zinc-400">No events recorded yet.</p>
                @endforelse
            </div>

            {{-- Match Statistics --}}
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                <h3 class="font-bold text-zinc-900 dark:text-white mb-4">Statistics</h3>
                @if(!empty($statistics))
                    @php
                        $homeStats = $statistics[0]['statistics'] ?? [];
                        $awayStats = $statistics[1]['statistics'] ?? [];
                        $homeTeamName = $statistics[0]['team']['name'] ?? $match->home_team['name'];
                        $awayTeamName = $statistics[1]['team']['name'] ?? $match->away_team['name'];
                    @endphp
                    <div class="text-xs text-zinc-500 flex justify-between mb-3">
                        <span>{{ $homeTeamName }}</span>
                        <span>{{ $awayTeamName }}</span>
                    </div>
                    @foreach($homeStats as $i => $stat)
                    @php
                        $homeStat = $stat['value'] ?? 0;
                        $awayStat = $awayStats[$i]['value'] ?? 0;
                        $statName = $stat['type'] ?? '';
                        // Calculate percentages for the bar
                        $homeVal = is_string($homeStat) ? (int) str_replace('%', '', $homeStat) : (int) $homeStat;
                        $awayVal = is_string($awayStat) ? (int) str_replace('%', '', $awayStat) : (int) $awayStat;
                        $total = $homeVal + $awayVal;
                        $homePct = $total > 0 ? round(($homeVal / $total) * 100) : 50;
                        $awayPct = 100 - $homePct;
                    @endphp
                    <div class="mb-3">
                        <div class="flex justify-between text-sm text-zinc-700 dark:text-zinc-300 mb-1">
                            <span>{{ $homeStat }}</span>
                            <span class="text-xs text-zinc-400">{{ $statName }}</span>
                            <span>{{ $awayStat }}</span>
                        </div>
                        <div class="flex h-1.5 rounded-full overflow-hidden bg-zinc-200 dark:bg-zinc-700">
                            <div class="bg-green-500 transition-all" style="width: {{ $homePct }}%"></div>
                            <div class="bg-blue-500 transition-all" style="width: {{ $awayPct }}%"></div>
                        </div>
                    </div>
                    @endforeach
                @else
                <p class="text-sm text-zinc-400">No statistics available yet.</p>
                @endif
            </div>
        </div>

        {{-- Lineups --}}
        @if($lineups->isNotEmpty())
        <div class="grid gap-6 lg:grid-cols-2">
            @foreach($lineups as $lineup)
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        @if($lineup->team['logo'] ?? null)
                        <img src="{{ $lineup->team['logo'] }}" alt="" class="h-5 w-5 object-contain">
                        @endif
                        <h3 class="font-bold text-zinc-900 dark:text-white">{{ $lineup->team['name'] }}</h3>
                    </div>
                    @if($lineup->formation)
                    <span class="text-xs bg-zinc-100 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-300 px-2 py-0.5 rounded">{{ $lineup->formation }}</span>
                    @endif
                </div>
                @if($lineup->coach['name'] ?? null)
                <div class="text-xs text-zinc-400 mb-3">Coach: {{ $lineup->coach['name'] }}</div>
                @endif
                <div class="space-y-1">
                    <div class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-2">Starting XI</div>
                    @foreach($lineup->start_xi as $player)
                    <div class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                        <span class="text-xs font-mono text-zinc-400 w-6 text-right">{{ $player['number'] }}</span>
                        <span class="text-xs text-zinc-400 w-6 text-center">{{ $player['pos'] }}</span>
                        <span>{{ $player['name'] }}</span>
                    </div>
                    @endforeach

                    @if(!empty($lineup->substitutes))
                    <div class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mt-4 mb-2">Substitutes</div>
                    @foreach($lineup->substitutes as $player)
                    <div class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                        <span class="text-xs font-mono text-zinc-400 w-6 text-right">{{ $player['number'] }}</span>
                        <span class="text-xs text-zinc-400 w-6 text-center">{{ $player['pos'] }}</span>
                        <span>{{ $player['name'] }}</span>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Additional Info --}}
        @if($match->referee || $match->venue)
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
            <h3 class="font-bold text-zinc-900 dark:text-white mb-3">Match Info</h3>
            <div class="grid gap-2 sm:grid-cols-2 text-sm">
                @if($match->referee)
                <div><span class="text-zinc-400">Referee:</span> <span class="text-zinc-800 dark:text-zinc-200">{{ $match->referee }}</span></div>
                @endif
                @if($match->venue)
                <div><span class="text-zinc-400">Venue:</span> <span class="text-zinc-800 dark:text-zinc-200">{{ $match->venue }}</span></div>
                @endif
            </div>
        </div>
        @endif

        <div class="text-center">
            <a href="{{ route('matches.index') }}" class="text-sm text-green-600 hover:text-green-700 dark:text-green-400">← Back to all matches</a>
        </div>
    </div>
</x-layouts::app>
