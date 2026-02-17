<x-layouts::app :title="$match->home_team['name'] . ' vs ' . $match->away_team['name']">
    <div class="max-w-7xl mx-auto space-y-8 p-2 sm:p-4">
        {{-- Hero Section --}}
        <div class="relative rounded-2xl overflow-hidden shadow-xl">
            {{-- Background gradient based on match status --}}
            @php
                $heroGradient = match($match->status) {
                    'live', 'half_time' => 'from-red-700 via-rose-600 to-red-800',
                    'finished' => 'from-zinc-700 via-zinc-600 to-zinc-800',
                    default => 'from-green-700 via-emerald-600 to-teal-600',
                };
            @endphp
            <div class="bg-gradient-to-r {{ $heroGradient }} p-6 sm:p-8 lg:p-10 text-white">
                <div class="absolute inset-0 opacity-5" style="background-image: url('https://images.unsplash.com/photo-1522778119026-d647f0596c20?w=1200&q=50'); background-size: cover; background-position: center;"></div>
                <div class="absolute top-0 right-0 w-80 h-80 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/3 -translate-x-1/4"></div>

                {{-- Competition & Date --}}
                <div class="relative z-10 text-center mb-6">
                    <div class="inline-flex items-center gap-2 rounded-xl bg-white/15 backdrop-blur-sm px-4 py-2 text-sm border border-white/10">
                        @if($match->league['logo'] ?? null)
                        <img src="{{ $match->league['logo'] }}" alt="" class="h-5 w-5 object-contain">
                        @endif
                        <span class="font-medium">{{ $match->league['name'] }}</span>
                        <span class="text-white/50">·</span>
                        <span class="text-white/80">{{ \Carbon\Carbon::parse($match->date)->format('l, M d, Y · H:i') }}</span>
                    </div>
                    @if($match->league['round'] ?? null)
                    <div class="mt-2 text-sm text-white/60">{{ $match->league['round'] }}</div>
                    @endif
                </div>

                {{-- Teams & Score --}}
                <div class="relative z-10 flex items-center justify-center gap-6 sm:gap-10 lg:gap-16">
                    {{-- Home Team --}}
                    <div class="flex-1 text-center">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 mx-auto mb-3 rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center p-2 border border-white/10">
                            @if($match->home_team['logo'] ?? null)
                            <img src="{{ $match->home_team['logo'] }}" alt="{{ $match->home_team['name'] }}" class="h-14 w-14 sm:h-16 sm:w-16 object-contain">
                            @else
                            <span class="text-4xl">⚽</span>
                            @endif
                        </div>
                        <div class="text-lg sm:text-xl font-bold">{{ $match->home_team['name'] }}</div>
                        <div class="text-xs text-white/50 mt-0.5 uppercase tracking-wider">Home</div>
                    </div>

                    {{-- Score --}}
                    <div class="text-center flex-shrink-0">
                        <div class="text-5xl sm:text-6xl font-black tabular-nums tracking-tight">{{ $match->score_display }}</div>
                        @php
                            $statusClasses = match($match->status) {
                                'live', 'half_time' => 'bg-white/20 text-white border-white/20',
                                'finished' => 'bg-white/15 text-white/80 border-white/10',
                                default => 'bg-white/15 text-white/80 border-white/10',
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full mt-3 {{ $statusClasses }} border backdrop-blur-sm">
                            @if(in_array($match->status, ['live', 'half_time']))
                            <span class="relative flex h-1.5 w-1.5"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span><span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-white"></span></span>
                            @endif
                            {{ $match->status_long }}
                            @if($match->elapsed && in_array($match->status, ['live', 'half_time']))
                            <span class="font-mono">{{ $match->elapsed }}'</span>
                            @endif
                        </span>
                    </div>

                    {{-- Away Team --}}
                    <div class="flex-1 text-center">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 mx-auto mb-3 rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center p-2 border border-white/10">
                            @if($match->away_team['logo'] ?? null)
                            <img src="{{ $match->away_team['logo'] }}" alt="{{ $match->away_team['name'] }}" class="h-14 w-14 sm:h-16 sm:w-16 object-contain">
                            @else
                            <span class="text-4xl">⚽</span>
                            @endif
                        </div>
                        <div class="text-lg sm:text-xl font-bold">{{ $match->away_team['name'] }}</div>
                        <div class="text-xs text-white/50 mt-0.5 uppercase tracking-wider">Away</div>
                    </div>
                </div>

                {{-- Score details --}}
                <div class="relative z-10 text-center mt-4 space-y-1">
                    @if($match->status === 'finished' && $match->ht_score['home'] !== null)
                    <div class="text-xs text-white/50">HT: {{ $match->ht_score['home'] }} - {{ $match->ht_score['away'] }}</div>
                    @endif
                    @if($match->et_score['home'] !== null)
                    <div class="text-xs text-white/50">ET: {{ $match->et_score['home'] }} - {{ $match->et_score['away'] }}</div>
                    @endif
                    @if($match->penalty_score['home'] !== null)
                    <div class="text-xs text-white/50">PEN: {{ $match->penalty_score['home'] }} - {{ $match->penalty_score['away'] }}</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Events & Statistics --}}
        <div class="grid gap-6 lg:grid-cols-2">
            {{-- Match Events --}}
            <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/10 dark:to-orange-900/10">
                    <h3 class="font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 text-sm">📋</span>
                        Match Events
                    </h3>
                </div>
                <div class="p-5">
                    @forelse($events as $event)
                    <div class="flex items-center gap-3 py-3 border-b border-zinc-100 dark:border-zinc-700/40 last:border-0 group hover:bg-zinc-50/50 dark:hover:bg-zinc-700/20 -mx-2 px-2 rounded-lg transition">
                        <span class="flex-shrink-0 w-10 text-xs font-mono text-zinc-400 dark:text-zinc-500 text-right tabular-nums">{{ $event->time }}</span>
                        <span class="flex-shrink-0 w-6 text-center text-base">{{ $event->icon }}</span>
                        <div class="flex-1 min-w-0">
                            <span class="text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $event->player['name'] }}</span>
                            @if($event->assist['name'] ?? null)
                            <span class="text-xs text-zinc-400 dark:text-zinc-500">({{ $event->assist['name'] }})</span>
                            @endif
                            @if($event->detail)
                            <div class="text-xs text-zinc-400 dark:text-zinc-500 mt-0.5">{{ $event->detail }}</div>
                            @endif
                        </div>
                        <span class="flex-shrink-0 text-xs text-zinc-400 dark:text-zinc-500 font-medium">{{ $event->team['name'] }}</span>
                    </div>
                    @empty
                    <div class="rounded-xl border-2 border-dashed border-zinc-200 dark:border-zinc-700 p-8 text-center">
                        <span class="text-3xl">📝</span>
                        <p class="text-sm text-zinc-400 mt-2">No events recorded yet.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Match Statistics --}}
            <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10">
                    <h3 class="font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 text-sm">📊</span>
                        Statistics
                    </h3>
                </div>
                <div class="p-5">
                    @if(!empty($statistics))
                        @php
                            $homeStats = $statistics[0]['statistics'] ?? [];
                            $awayStats = $statistics[1]['statistics'] ?? [];
                            $homeTeamName = $statistics[0]['team']['name'] ?? $match->home_team['name'];
                            $awayTeamName = $statistics[1]['team']['name'] ?? $match->away_team['name'];
                        @endphp
                        <div class="flex justify-between mb-4 text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                            <span>{{ $homeTeamName }}</span>
                            <span>{{ $awayTeamName }}</span>
                        </div>
                        @foreach($homeStats as $i => $stat)
                        @php
                            $homeStat = $stat['value'] ?? 0;
                            $awayStat = $awayStats[$i]['value'] ?? 0;
                            $statName = $stat['type'] ?? '';
                            $homeVal = is_string($homeStat) ? (int) str_replace('%', '', $homeStat) : (int) $homeStat;
                            $awayVal = is_string($awayStat) ? (int) str_replace('%', '', $awayStat) : (int) $awayStat;
                            $total = $homeVal + $awayVal;
                            $homePct = $total > 0 ? round(($homeVal / $total) * 100) : 50;
                            $awayPct = 100 - $homePct;
                        @endphp
                        <div class="mb-4">
                            <div class="flex justify-between text-sm mb-1.5">
                                <span class="font-semibold text-zinc-800 dark:text-zinc-200 tabular-nums">{{ $homeStat }}</span>
                                <span class="text-xs text-zinc-400 dark:text-zinc-500 font-medium">{{ $statName }}</span>
                                <span class="font-semibold text-zinc-800 dark:text-zinc-200 tabular-nums">{{ $awayStat }}</span>
                            </div>
                            <div class="flex h-2 rounded-full overflow-hidden bg-zinc-100 dark:bg-zinc-700 gap-0.5">
                                <div class="bg-gradient-to-r from-green-500 to-emerald-400 rounded-full transition-all duration-700" style="width: {{ $homePct }}%"></div>
                                <div class="bg-gradient-to-r from-blue-400 to-blue-500 rounded-full transition-all duration-700" style="width: {{ $awayPct }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    @else
                    <div class="rounded-xl border-2 border-dashed border-zinc-200 dark:border-zinc-700 p-8 text-center">
                        <span class="text-3xl">📊</span>
                        <p class="text-sm text-zinc-400 mt-2">No statistics available yet.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Lineups --}}
        @if($lineups->isNotEmpty())
        <div class="grid gap-6 lg:grid-cols-2">
            @foreach($lineups as $lineup)
            <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/10 dark:to-emerald-900/10">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            @if($lineup->team['logo'] ?? null)
                            <img src="{{ $lineup->team['logo'] }}" alt="" class="h-6 w-6 object-contain">
                            @endif
                            <h3 class="font-bold text-zinc-900 dark:text-white">{{ $lineup->team['name'] }}</h3>
                        </div>
                        @if($lineup->formation)
                        <span class="text-xs font-bold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-3 py-1 rounded-lg">{{ $lineup->formation }}</span>
                        @endif
                    </div>
                    @if($lineup->coach['name'] ?? null)
                    <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-1.5">🧑‍💼 Coach: {{ $lineup->coach['name'] }}</div>
                    @endif
                </div>
                <div class="p-5 space-y-1">
                    <div class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mb-3">Starting XI</div>
                    @foreach($lineup->start_xi as $player)
                    <div class="flex items-center gap-2.5 py-1.5 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700/30 -mx-2 px-2 rounded-lg transition">
                        <span class="w-7 h-7 flex items-center justify-center rounded-lg bg-green-50 dark:bg-green-900/20 text-[11px] font-bold text-green-700 dark:text-green-400 tabular-nums">{{ $player['number'] }}</span>
                        <span class="text-[10px] font-semibold text-zinc-400 uppercase w-6 text-center">{{ $player['pos'] }}</span>
                        <span class="font-medium">{{ $player['name'] }}</span>
                    </div>
                    @endforeach

                    @if(!empty($lineup->substitutes))
                    <div class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mt-5 mb-3 pt-4 border-t border-zinc-100 dark:border-zinc-700/40">Substitutes</div>
                    @foreach($lineup->substitutes as $player)
                    <div class="flex items-center gap-2.5 py-1.5 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700/30 -mx-2 px-2 rounded-lg transition">
                        <span class="w-7 h-7 flex items-center justify-center rounded-lg bg-zinc-100 dark:bg-zinc-700 text-[11px] font-bold text-zinc-500 dark:text-zinc-400 tabular-nums">{{ $player['number'] }}</span>
                        <span class="text-[10px] font-semibold text-zinc-400 uppercase w-6 text-center">{{ $player['pos'] }}</span>
                        <span class="font-medium">{{ $player['name'] }}</span>
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
        <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-violet-50 to-purple-50 dark:from-violet-900/10 dark:to-purple-900/10">
                <h3 class="font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center text-violet-600 text-sm">ℹ️</span>
                    Match Info
                </h3>
            </div>
            <div class="p-5 grid gap-4 sm:grid-cols-2">
                @if($match->referee)
                <div class="flex items-center gap-3 rounded-xl bg-zinc-50 dark:bg-zinc-700/30 p-3">
                    <span class="w-8 h-8 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center text-sm">🟨</span>
                    <div>
                        <div class="text-[10px] font-semibold text-zinc-400 uppercase tracking-wider">Referee</div>
                        <div class="text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $match->referee }}</div>
                    </div>
                </div>
                @endif
                @if($match->venue)
                <div class="flex items-center gap-3 rounded-xl bg-zinc-50 dark:bg-zinc-700/30 p-3">
                    <span class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-sm">🏟</span>
                    <div>
                        <div class="text-[10px] font-semibold text-zinc-400 uppercase tracking-wider">Venue</div>
                        <div class="text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $match->venue }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Back link --}}
        <div class="text-center pb-4">
            <a href="{{ route('matches.index') }}" class="inline-flex items-center gap-2 text-sm text-green-600 hover:text-green-700 dark:text-green-400 font-medium transition hover:-translate-x-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to all matches
            </a>
        </div>
    </div>
</x-layouts::app>
