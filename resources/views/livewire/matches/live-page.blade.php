<div wire:poll.30s>
    <div class="max-w-7xl mx-auto space-y-8 p-2 sm:p-4">
        {{-- Live Banner --}}
        <div class="relative rounded-2xl overflow-hidden bg-gradient-to-r from-red-700 via-rose-600 to-orange-600 p-6 sm:p-8 text-white shadow-xl">
            <div class="absolute inset-0 opacity-10" style="background-image: url('https://images.unsplash.com/photo-1489944440615-453fc2b6a9a9?w=600&q=30'); background-size: cover; background-position: center;"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/3 -translate-x-1/4"></div>
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold mb-2 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
                            </span>
                        </span>
                        Live Matches
                    </h1>
                    <p class="text-red-100 text-sm sm:text-base">Watch the action unfold in real-time. Scores update automatically.</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-2 rounded-xl bg-white/15 backdrop-blur-sm px-4 py-2 text-xs font-medium border border-white/20">
                        <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Last updated: {{ now()->format('H:i:s') }}
                    </span>
                    <a href="{{ route('matches.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-white/20 backdrop-blur-sm hover:bg-white/30 px-5 py-2.5 text-sm font-semibold text-white transition-all border border-white/20 hover:scale-105">
                        All Matches
                    </a>
                </div>
            </div>
        </div>

        {{-- Live Match Cards --}}
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($matches as $match)
            <a href="{{ route('matches.show', $match->id) }}" class="group block rounded-2xl border-2 border-red-200/80 dark:border-red-800/60 bg-white dark:bg-zinc-800 shadow-sm hover:shadow-xl hover:shadow-red-500/10 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                {{-- League Bar --}}
                <div class="flex items-center justify-between px-5 py-3 bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20 border-b border-red-100 dark:border-red-800/40">
                    <div class="flex items-center gap-2">
                        @if($match->league['logo'] ?? null)
                        <img loading="lazy" decoding="async" src="{{ $match->league['logo'] }}" alt="" class="h-4 w-4 object-contain">
                        @endif
                        <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400">{{ $match->league['name'] }}</span>
                    </div>
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 px-2.5 py-1 rounded-full">
                        <span class="relative flex h-1.5 w-1.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-red-500"></span>
                        </span>
                        {{ $match->elapsed ?? '—' }}'
                    </span>
                </div>

                {{-- Teams & Score --}}
                <div class="p-5">
                    <div class="flex items-center justify-between gap-3">
                        {{-- Home --}}
                        <div class="flex-1 text-center">
                            <div class="w-14 h-14 mx-auto mb-2 rounded-xl bg-zinc-50 dark:bg-zinc-700/50 flex items-center justify-center p-1.5 group-hover:scale-110 transition-transform duration-300">
                                @if($match->home_team['logo'] ?? null)
                                <img loading="lazy" decoding="async" src="{{ $match->home_team['logo'] }}" alt="" class="h-10 w-10 object-contain">
                                @else
                                <span class="text-2xl">⚽</span>
                                @endif
                            </div>
                            <div class="text-sm font-bold text-zinc-900 dark:text-white leading-tight">{{ $match->home_team['name'] }}</div>
                        </div>

                        {{-- Score --}}
                        <div class="text-center px-3 flex-shrink-0">
                            <div class="text-3xl sm:text-4xl font-black text-red-600 dark:text-red-500 tabular-nums tracking-tight">{{ $match->score_display }}</div>
                            <div class="mt-1.5 text-[10px] font-semibold uppercase tracking-wider text-red-500 dark:text-red-400">{{ ucfirst(str_replace('_', ' ', $match->status)) }}</div>
                        </div>

                        {{-- Away --}}
                        <div class="flex-1 text-center">
                            <div class="w-14 h-14 mx-auto mb-2 rounded-xl bg-zinc-50 dark:bg-zinc-700/50 flex items-center justify-center p-1.5 group-hover:scale-110 transition-transform duration-300">
                                @if($match->away_team['logo'] ?? null)
                                <img loading="lazy" decoding="async" src="{{ $match->away_team['logo'] }}" alt="" class="h-10 w-10 object-contain">
                                @else
                                <span class="text-2xl">⚽</span>
                                @endif
                            </div>
                            <div class="text-sm font-bold text-zinc-900 dark:text-white leading-tight">{{ $match->away_team['name'] }}</div>
                        </div>
                    </div>

                    {{-- Events Ticker --}}
                    @if($match->events->count())
                    <div class="mt-4 pt-3 border-t border-zinc-100 dark:border-zinc-700/60 space-y-1.5">
                        @foreach($match->events->take(3) as $event)
                        <div class="flex items-center gap-2 text-xs text-zinc-500 dark:text-zinc-400">
                            <span class="font-mono text-[10px] text-zinc-400 w-8">{{ $event->time }}</span>
                            <span>{{ $event->icon }}</span>
                            <span class="truncate">{{ $event->player['name'] }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </a>
            @empty
            {{-- Empty State --}}
            <div class="col-span-full">
                <div class="rounded-2xl border-2 border-dashed border-zinc-200 dark:border-zinc-700 p-12 text-center">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                        <span class="text-4xl">⚽</span>
                    </div>
                    <h2 class="text-xl font-bold text-zinc-700 dark:text-zinc-300 mb-2">No Live Matches Right Now</h2>
                    <p class="text-sm text-zinc-400 dark:text-zinc-500 mx-auto">There are no matches being played at the moment. Check back later for live action!</p>
                    <a href="{{ route('matches.index') }}" class="inline-flex items-center gap-2 mt-6 rounded-xl bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-2.5 text-sm font-semibold text-white hover:from-green-500 hover:to-emerald-400 transition-all shadow-md shadow-green-600/20 hover:scale-105">
                        Browse All Matches →
                    </a>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
