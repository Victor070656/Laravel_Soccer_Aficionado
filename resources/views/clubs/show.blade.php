<x-layouts::app :title="$club->name">
    <div class="max-w-7xl mx-auto space-y-8 p-2 sm:p-4">
        {{-- Club Hero Section --}}
        <div class="relative rounded-2xl overflow-hidden shadow-xl">
            @if($club->venue['image'] ?? null)
            <div class="h-48 sm:h-56 bg-cover bg-center" style="background-image: url('{{ $club->venue['image'] }}')">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-black/10"></div>
            </div>
            @else
            <div class="h-48 sm:h-56 bg-gradient-to-r from-green-700 via-emerald-600 to-teal-600">
                <div class="absolute inset-0 opacity-10" style="background-image: url('https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?w=600&q=30'); background-size: cover; background-position: center;"></div>
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/3 -translate-x-1/4"></div>
            </div>
            @endif

            {{-- Club Info Overlay --}}
            <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8">
                <div class="flex items-end gap-4">
                    <div class="flex-shrink-0 w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-white/95 dark:bg-zinc-800/95 backdrop-blur-sm flex items-center justify-center p-2 shadow-xl border border-white/20">
                        @if($club->logo)
                        <img loading="lazy" decoding="async" src="{{ $club->logo }}" alt="{{ $club->name }}" class="w-14 h-14 sm:w-16 sm:h-16 object-contain">
                        @else
                        <span class="text-2xl font-bold text-green-600">{{ strtoupper(substr($club->code ?? $club->name, 0, 3)) }}</span>
                        @endif
                    </div>
                    <div class="flex-1 text-white pb-1">
                        <h1 class="text-2xl sm:text-3xl font-bold drop-shadow-lg">{{ $club->name }}</h1>
                        <div class="flex items-center gap-3 text-sm text-white/80 mt-1 flex-wrap">
                            @if($club->country) <span>🌍 {{ $club->country }}</span> @endif
                            @if($club->venue['city'] ?? null) <span>📍 {{ $club->venue['city'] }}</span> @endif
                            @if($club->founded) <span>📅 Est. {{ $club->founded }}</span> @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Venue Info Bar --}}
        @if(($club->venue['name'] ?? null) || ($club->venue['capacity'] ?? null))
        <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm p-4 flex flex-wrap items-center gap-4">
            @if($club->venue['name'] ?? null)
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 text-sm">🏟</span>
                <div>
                    <div class="text-[10px] font-semibold text-zinc-400 uppercase tracking-wider">Stadium</div>
                    <div class="text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $club->venue['name'] }}</div>
                </div>
            </div>
            @endif
            @if($club->venue['capacity'] ?? null)
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 text-sm">🪑</span>
                <div>
                    <div class="text-[10px] font-semibold text-zinc-400 uppercase tracking-wider">Capacity</div>
                    <div class="text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ number_format($club->venue['capacity']) }}</div>
                </div>
            </div>
            @endif
        </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-3">
            {{-- Squad --}}
            <div class="lg:col-span-2 rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/10 dark:to-emerald-900/10">
                    <h3 class="font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 text-sm">👕</span>
                        Squad
                    </h3>
                </div>
                <div class="p-5">
                    @if($squad->isNotEmpty())
                    @php $positions = $squad->groupBy('position'); @endphp
                    @foreach(['Goalkeeper', 'Defender', 'Midfielder', 'Attacker'] as $position)
                        @if(isset($positions[$position]))
                        <div class="mb-5 last:mb-0">
                            @php
                                $posColors = match($position) {
                                    'Goalkeeper' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400',
                                    'Defender' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
                                    'Midfielder' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
                                    'Attacker' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
                                    default => 'bg-zinc-100 dark:bg-zinc-700 text-zinc-600',
                                };
                            @endphp
                            <div class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-widest mb-3 px-2.5 py-1 rounded-lg {{ $posColors }}">{{ $position }}s</div>
                            <div class="grid gap-2 sm:grid-cols-2">
                                @foreach($positions[$position] as $player)
                                <div class="flex items-center justify-between py-2.5 px-3 rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-700/30 transition group">
                                    <div class="flex items-center gap-3">
                                        @if($player->number)
                                        <span class="w-8 h-8 flex items-center justify-center rounded-lg {{ $posColors }} text-xs font-bold tabular-nums">{{ $player->number }}</span>
                                        @endif
                                        <div class="flex items-center gap-2">
                                            @if($player->photo)
                                            <img loading="lazy" decoding="async" src="{{ $player->photo }}" alt="" class="w-8 h-8 rounded-full object-cover ring-2 ring-zinc-100 dark:ring-zinc-700">
                                            @endif
                                            <span class="text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $player->name }}</span>
                                        </div>
                                    </div>
                                    @if($player->age)
                                    <span class="text-xs text-zinc-400 dark:text-zinc-500 font-medium">{{ $player->age }} yrs</span>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endforeach
                    @else
                    <div class="rounded-xl border-2 border-dashed border-zinc-200 dark:border-zinc-700 p-8 text-center">
                        <span class="text-3xl">👕</span>
                        <p class="text-sm text-zinc-400 mt-2">Squad information not available.</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar: Matches --}}
            <div class="space-y-6">
                {{-- Recent Results --}}
                <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/10 dark:to-orange-900/10">
                        <h3 class="font-bold text-zinc-900 dark:text-white flex items-center gap-2 text-sm">
                            <span class="w-7 h-7 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 text-xs">📊</span>
                            Recent Results
                        </h3>
                    </div>
                    <div class="p-4">
                        @forelse($recentMatches as $match)
                        <a href="{{ route('matches.show', $match->id) }}" class="block py-2.5 border-b border-zinc-100 dark:border-zinc-700/40 last:border-0 hover:bg-zinc-50 dark:hover:bg-zinc-700/30 -mx-2 px-2 rounded-lg transition">
                            <div class="flex justify-between items-center text-sm">
                                <div class="flex items-center gap-1.5 flex-1 min-w-0">
                                    @if($match->home_team['logo'] ?? null)
                                    <img loading="lazy" decoding="async" src="{{ $match->home_team['logo'] }}" alt="" class="h-4 w-4 object-contain flex-shrink-0">
                                    @endif
                                    <span class="font-medium text-zinc-800 dark:text-zinc-200 truncate">{{ $match->home_team['name'] }}</span>
                                </div>
                                <span class="font-bold text-zinc-900 dark:text-white px-2 flex-shrink-0">{{ $match->score_display }}</span>
                                <div class="flex items-center gap-1.5 flex-1 min-w-0 justify-end">
                                    <span class="font-medium text-zinc-800 dark:text-zinc-200 truncate">{{ $match->away_team['name'] }}</span>
                                    @if($match->away_team['logo'] ?? null)
                                    <img loading="lazy" decoding="async" src="{{ $match->away_team['logo'] }}" alt="" class="h-4 w-4 object-contain flex-shrink-0">
                                    @endif
                                </div>
                            </div>
                        </a>
                        @empty
                        <p class="text-sm text-zinc-400 dark:text-zinc-500 text-center py-4">No recent results.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Upcoming --}}
                <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10">
                        <h3 class="font-bold text-zinc-900 dark:text-white flex items-center gap-2 text-sm">
                            <span class="w-7 h-7 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 text-xs">📅</span>
                            Upcoming Matches
                        </h3>
                    </div>
                    <div class="p-4">
                        @forelse($upcomingMatches as $match)
                        <a href="{{ route('matches.show', $match->id) }}" class="block py-2.5 border-b border-zinc-100 dark:border-zinc-700/40 last:border-0 hover:bg-zinc-50 dark:hover:bg-zinc-700/30 -mx-2 px-2 rounded-lg transition">
                            <div class="text-[11px] text-zinc-400 dark:text-zinc-500 font-medium">{{ \Carbon\Carbon::parse($match->date)->format('M d, H:i') }}</div>
                            <div class="flex justify-between items-center text-sm font-medium text-zinc-800 dark:text-zinc-200 mt-1">
                                <div class="flex items-center gap-1.5">
                                    @if($match->home_team['logo'] ?? null)
                                    <img loading="lazy" decoding="async" src="{{ $match->home_team['logo'] }}" alt="" class="h-4 w-4 object-contain">
                                    @endif
                                    <span>{{ $match->home_team['name'] }}</span>
                                </div>
                                <span class="text-zinc-400 text-xs font-semibold">VS</span>
                                <div class="flex items-center gap-1.5">
                                    <span>{{ $match->away_team['name'] }}</span>
                                    @if($match->away_team['logo'] ?? null)
                                    <img loading="lazy" decoding="async" src="{{ $match->away_team['logo'] }}" alt="" class="h-4 w-4 object-contain">
                                    @endif
                                </div>
                            </div>
                        </a>
                        @empty
                        <p class="text-sm text-zinc-400 dark:text-zinc-500 text-center py-4">No upcoming matches.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Back link --}}
        <div class="text-center pb-4">
            <a href="{{ route('clubs.index') }}" class="inline-flex items-center gap-2 text-sm text-green-600 hover:text-green-700 dark:text-green-400 font-medium transition hover:-translate-x-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to all clubs
            </a>
        </div>
    </div>
</x-layouts::app>
