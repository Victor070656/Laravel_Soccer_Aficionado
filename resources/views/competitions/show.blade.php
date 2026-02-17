<x-layouts::app :title="$competition->name">
    <div class="max-w-7xl mx-auto space-y-8 p-2 sm:p-4">
        {{-- Header --}}
        <div class="relative rounded-2xl overflow-hidden bg-gradient-to-r from-amber-600 via-yellow-600 to-orange-600 p-6 sm:p-8 text-white shadow-xl">
            <div class="absolute inset-0 opacity-10" style="background-image: url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=600&q=30'); background-size: cover; background-position: center;"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/3 -translate-x-1/4"></div>
            <div class="relative z-10 flex items-center gap-4">
                @if($competition->logo)
                <div class="w-16 h-16 rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center p-2 border border-white/10">
                    <img loading="lazy" decoding="async" src="{{ $competition->logo }}" alt="{{ $competition->name }}" class="w-10 h-10 object-contain">
                </div>
                @endif
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold">{{ $competition->name }}</h1>
                    <div class="flex items-center gap-2 text-sm text-amber-100 mt-1">
                        @if($competition->country_flag ?? null)
                        <img loading="lazy" decoding="async" src="{{ $competition->country_flag }}" alt="" class="h-3 w-4 object-contain">
                        @endif
                        {{ ucfirst($competition->type) }} · {{ $competition->country ?? 'International' }} · Season {{ $seasonDisplay }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Standings --}}
        @if($standings->isNotEmpty())
        <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-amber-900/10 dark:to-yellow-900/10">
                <h3 class="font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 text-sm">🏆</span>
                    Standings
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-zinc-50 dark:bg-zinc-900/50 text-[11px] text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left w-12">#</th>
                            <th class="px-4 py-3 text-left">Club</th>
                            <th class="px-4 py-3 text-center">P</th>
                            <th class="px-4 py-3 text-center">W</th>
                            <th class="px-4 py-3 text-center">D</th>
                            <th class="px-4 py-3 text-center">L</th>
                            <th class="px-4 py-3 text-center">GF</th>
                            <th class="px-4 py-3 text-center">GA</th>
                            <th class="px-4 py-3 text-center">GD</th>
                            <th class="px-4 py-3 text-center font-bold">Pts</th>
                            <th class="px-4 py-3 text-center">Form</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700/50">
                        @foreach($standings as $standing)
                        @php
                            $rankBg = match(true) {
                                $standing->rank <= 4 => 'border-l-4 border-l-green-500',
                                $standing->rank >= count($standings->toArray()) - 2 => 'border-l-4 border-l-red-500',
                                default => 'border-l-4 border-l-transparent',
                            };
                        @endphp
                        <tr class="{{ $rankBg }} hover:bg-zinc-50 dark:hover:bg-zinc-700/30 transition">
                            <td class="px-4 py-3">
                                <span class="font-bold text-zinc-600 dark:text-zinc-300 tabular-nums">{{ $standing->rank }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('clubs.show', $standing->team['id']) }}" class="flex items-center gap-2.5 font-medium text-zinc-900 dark:text-white hover:text-green-600 transition">
                                    @if($standing->team['logo'] ?? null)
                                    <img loading="lazy" decoding="async" src="{{ $standing->team['logo'] }}" alt="" class="h-5 w-5 object-contain">
                                    @endif
                                    <span class="truncate">{{ $standing->team['name'] }}</span>
                                </a>
                            </td>
                            <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-300 tabular-nums">{{ $standing->played }}</td>
                            <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-300 tabular-nums">{{ $standing->won }}</td>
                            <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-300 tabular-nums">{{ $standing->drawn }}</td>
                            <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-300 tabular-nums">{{ $standing->lost }}</td>
                            <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-300 tabular-nums">{{ $standing->goals_for }}</td>
                            <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-300 tabular-nums">{{ $standing->goals_against }}</td>
                            <td class="px-4 py-3 text-center font-semibold tabular-nums {{ $standing->goals_diff >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ $standing->goals_diff > 0 ? '+' : '' }}{{ $standing->goals_diff }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="font-black text-zinc-900 dark:text-white text-base tabular-nums">{{ $standing->points }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-0.5">
                                    @foreach(str_split($standing->form) as $result)
                                    <span class="w-5 h-5 rounded text-[10px] flex items-center justify-center font-bold {{ match($result) { 'W' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400', 'D' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400', 'L' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400', default => 'bg-zinc-100 dark:bg-zinc-700 text-zinc-500' } }}">{{ $result }}</span>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Teams --}}
        @if($teams->isNotEmpty())
        <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10">
                <h3 class="font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 text-sm">⚽</span>
                    Teams
                </h3>
            </div>
            <div class="p-5">
                <div class="grid gap-3 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach($teams as $team)
                    <a href="{{ route('clubs.show', $team->id) }}" class="flex items-center gap-2.5 p-3 rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-700/30 transition group border border-transparent hover:border-zinc-200 dark:hover:border-zinc-600">
                        @if($team->logo)
                        <img loading="lazy" decoding="async" src="{{ $team->logo }}" alt="" class="h-8 w-8 object-contain group-hover:scale-110 transition-transform">
                        @endif
                        <span class="text-sm font-medium text-zinc-800 dark:text-zinc-200 group-hover:text-green-600 transition">{{ $team->name }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Matches --}}
        <div class="grid gap-6 lg:grid-cols-2">
            {{-- Upcoming --}}
            <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/10 dark:to-emerald-900/10">
                    <h3 class="font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 text-sm">📅</span>
                        Upcoming Matches
                    </h3>
                </div>
                <div class="p-4">
                    @forelse($upcomingMatches as $match)
                    <a href="{{ route('matches.show', $match->id) }}" class="block py-3 border-b border-zinc-100 dark:border-zinc-700/40 last:border-0 hover:bg-zinc-50 dark:hover:bg-zinc-700/30 -mx-2 px-2 rounded-lg transition">
                        <div class="text-[11px] text-zinc-400 dark:text-zinc-500 font-medium">{{ \Carbon\Carbon::parse($match->date)->format('M d, H:i') }}</div>
                        <div class="flex justify-between items-center text-sm font-medium text-zinc-800 dark:text-zinc-200 mt-1">
                            <div class="flex items-center gap-1.5">
                                @if($match->home_team['logo'] ?? null)
                                <img loading="lazy" decoding="async" src="{{ $match->home_team['logo'] }}" alt="" class="h-4 w-4 object-contain">
                                @endif
                                <span>{{ $match->home_team['name'] }}</span>
                            </div>
                            <span class="text-zinc-400 text-xs font-bold px-2">VS</span>
                            <div class="flex items-center gap-1.5">
                                <span>{{ $match->away_team['name'] }}</span>
                                @if($match->away_team['logo'] ?? null)
                                <img loading="lazy" decoding="async" src="{{ $match->away_team['logo'] }}" alt="" class="h-4 w-4 object-contain">
                                @endif
                            </div>
                        </div>
                        @if($match->league['round'] ?? null)
                        <div class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-1">{{ $match->league['round'] }}</div>
                        @endif
                    </a>
                    @empty
                    <div class="rounded-xl border-2 border-dashed border-zinc-200 dark:border-zinc-700 p-8 text-center">
                        <span class="text-2xl">📅</span>
                        <p class="text-sm text-zinc-400 mt-2">No upcoming matches.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Results --}}
            <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-violet-50 to-purple-50 dark:from-violet-900/10 dark:to-purple-900/10">
                    <h3 class="font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center text-violet-600 text-sm">📊</span>
                        Recent Results
                    </h3>
                </div>
                <div class="p-4">
                    @forelse($recentResults as $match)
                    <a href="{{ route('matches.show', $match->id) }}" class="block py-3 border-b border-zinc-100 dark:border-zinc-700/40 last:border-0 hover:bg-zinc-50 dark:hover:bg-zinc-700/30 -mx-2 px-2 rounded-lg transition">
                        <div class="flex justify-between items-center text-sm">
                            <div class="flex items-center gap-1.5 flex-1 min-w-0">
                                @if($match->home_team['logo'] ?? null)
                                <img loading="lazy" decoding="async" src="{{ $match->home_team['logo'] }}" alt="" class="h-4 w-4 object-contain flex-shrink-0">
                                @endif
                                <span class="font-medium text-zinc-800 dark:text-zinc-200 truncate">{{ $match->home_team['name'] }}</span>
                            </div>
                            <span class="font-bold text-zinc-900 dark:text-white px-3 flex-shrink-0">{{ $match->score_display }}</span>
                            <div class="flex items-center gap-1.5 flex-1 min-w-0 justify-end">
                                <span class="font-medium text-zinc-800 dark:text-zinc-200 truncate">{{ $match->away_team['name'] }}</span>
                                @if($match->away_team['logo'] ?? null)
                                <img loading="lazy" decoding="async" src="{{ $match->away_team['logo'] }}" alt="" class="h-4 w-4 object-contain flex-shrink-0">
                                @endif
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="rounded-xl border-2 border-dashed border-zinc-200 dark:border-zinc-700 p-8 text-center">
                        <span class="text-2xl">📊</span>
                        <p class="text-sm text-zinc-400 mt-2">No recent results.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Back link --}}
        <div class="text-center pb-4">
            <a href="{{ route('competitions.index') }}" class="inline-flex items-center gap-2 text-sm text-green-600 hover:text-green-700 dark:text-green-400 font-medium transition hover:-translate-x-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to all competitions
            </a>
        </div>
    </div>
</x-layouts::app>
