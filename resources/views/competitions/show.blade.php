<x-layouts::app :title="$competition->name">
    <div class="min-h-screen bg-surface py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="glass-card rounded-xl p-6 relative overflow-hidden">
                @if($competition->logo ?? null)
                    <img loading="lazy" decoding="async" src="{{ $competition->logo }}" alt="" class="absolute inset-0 h-full w-full object-cover opacity-10">
                @endif
                <div class="relative z-10 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-4">
                        @if($competition->logo ?? null)
                            <div class="h-16 w-16 rounded-2xl bg-surface-container-high flex items-center justify-center overflow-hidden">
                                <img loading="lazy" decoding="async" src="{{ $competition->logo }}" alt="{{ $competition->name }}" class="h-10 w-10 object-contain">
                            </div>
                        @endif
                        <div>
                            <h1 class="text-headline-lg text-on-surface">League: {{ $competition->name }}</h1>
                            <p class="mt-1 text-body-md text-on-surface-variant">{{ ucfirst($competition->type) }} · {{ $competition->country ?? 'International' }}</p>
                        </div>
                    </div>
                    <form method="GET" action="{{ route('competitions.show', $competition->id) }}" class="flex items-center gap-2">
                        <label for="season" class="text-sm text-on-surface-variant hidden sm:inline">Season</label>
                        <select name="season" id="season" onchange="this.form.submit()" class="rounded-xl border border-outline-variant/30 bg-surface-container-high px-3 py-2 text-sm text-on-surface">
                            @foreach($availableSeasons as $season)
                                <option value="{{ $season['value'] }}" {{ $selectedSeason === $season['value'] ? 'selected' : '' }}>{{ $season['label'] }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            @unless($isCurrentSeason)
                <div class="glass-card rounded-xl p-4">
                    <p class="text-sm text-on-surface-variant">You are viewing the <strong>{{ $seasonDisplay }}</strong> season.</p>
                </div>
            @endunless

            @if($standings->isNotEmpty())
                <div class="glass-card rounded-xl overflow-hidden">
                    <div class="border-b border-outline-variant/20 px-5 py-4">
                        <h3 class="font-bold text-on-surface">Standings — {{ $seasonDisplay }}</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-surface-container-high/60 text-xs uppercase tracking-wider text-on-surface-variant">
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
                            <tbody class="divide-y divide-outline-variant/20">
                                @foreach($standings as $standing)
                                    <tr class="hover:bg-surface-container-high/30">
                                        <td class="px-4 py-3 font-bold text-on-surface-variant">{{ $standing->rank }}</td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('clubs.show', $standing->team['id']) }}" class="flex items-center gap-2 font-medium text-on-surface hover:text-primary-container">
                                                @if($standing->team['logo'] ?? null)
                                                    <img loading="lazy" decoding="async" src="{{ $standing->team['logo'] }}" alt="" class="h-5 w-5 object-contain">
                                                @endif
                                                <span class="truncate">{{ $standing->team['name'] }}</span>
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-center text-on-surface-variant">{{ $standing->played }}</td>
                                        <td class="px-4 py-3 text-center text-on-surface-variant">{{ $standing->won }}</td>
                                        <td class="px-4 py-3 text-center text-on-surface-variant">{{ $standing->drawn }}</td>
                                        <td class="px-4 py-3 text-center text-on-surface-variant">{{ $standing->lost }}</td>
                                        <td class="px-4 py-3 text-center text-on-surface-variant">{{ $standing->goals_for }}</td>
                                        <td class="px-4 py-3 text-center text-on-surface-variant">{{ $standing->goals_against }}</td>
                                        <td class="px-4 py-3 text-center font-semibold {{ $standing->goals_diff >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ $standing->goals_diff > 0 ? '+' : '' }}{{ $standing->goals_diff }}</td>
                                        <td class="px-4 py-3 text-center font-black text-on-surface">{{ $standing->points }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex justify-center gap-0.5">
                                                @foreach(str_split($standing->form) as $result)
                                                    <span class="w-5 h-5 rounded text-[10px] flex items-center justify-center font-bold {{ match($result) { 'W' => 'bg-green-100 text-green-700', 'D' => 'bg-yellow-100 text-yellow-700', 'L' => 'bg-red-100 text-red-700', default => 'bg-surface-container-high text-on-surface-variant' } }}">{{ $result }}</span>
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

            @if($teams->isNotEmpty())
                <div class="glass-card rounded-xl p-5">
                    <h3 class="font-bold text-on-surface">Teams</h3>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                        @foreach($teams as $team)
                            <a href="{{ route('clubs.show', $team->id) }}" class="flex items-center gap-2 rounded-xl border border-outline-variant/20 bg-surface-container-high/60 p-3 hover:bg-surface-container-high transition-colors">
                                @if($team->logo)
                                    <img loading="lazy" decoding="async" src="{{ $team->logo }}" alt="" class="h-8 w-8 object-contain">
                                @endif
                                <span class="text-sm font-medium text-on-surface">{{ $team->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($isCurrentSeason)
                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="glass-card rounded-xl overflow-hidden">
                        <div class="border-b border-outline-variant/20 px-5 py-4">
                            <h3 class="font-bold text-on-surface">Upcoming Matches</h3>
                        </div>
                        <div class="p-4">
                            @forelse($upcomingMatches as $match)
                                <a href="{{ route('matches.show', $match->id) }}" class="block rounded-lg px-2 py-3 hover:bg-surface-container-high/50 transition-colors">
                                    <div class="text-xs text-on-surface-variant">{{ \Carbon\Carbon::parse($match->date)->format('M d, H:i') }}</div>
                                    <div class="mt-1 flex items-center justify-between gap-3 text-sm">
                                        <span class="truncate text-on-surface">{{ $match->home_team['name'] }}</span>
                                        <span class="text-on-surface-variant">VS</span>
                                        <span class="truncate text-on-surface text-right">{{ $match->away_team['name'] }}</span>
                                    </div>
                                </a>
                            @empty
                                <div class="rounded-xl border border-dashed border-outline-variant/30 p-8 text-center text-sm text-on-surface-variant">No upcoming matches.</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="glass-card rounded-xl overflow-hidden">
                        <div class="border-b border-outline-variant/20 px-5 py-4">
                            <h3 class="font-bold text-on-surface">Recent Results</h3>
                        </div>
                        <div class="p-4">
                            @forelse($recentResults as $match)
                                <a href="{{ route('matches.show', $match->id) }}" class="block rounded-lg px-2 py-3 hover:bg-surface-container-high/50 transition-colors">
                                    <div class="flex items-center justify-between gap-3 text-sm">
                                        <span class="truncate text-on-surface">{{ $match->home_team['name'] }}</span>
                                        <span class="font-bold text-on-surface">{{ $match->score_display }}</span>
                                        <span class="truncate text-on-surface text-right">{{ $match->away_team['name'] }}</span>
                                    </div>
                                </a>
                            @empty
                                <div class="rounded-xl border border-dashed border-outline-variant/30 p-8 text-center text-sm text-on-surface-variant">No recent results.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

            <div class="text-center pb-4">
                <a href="{{ route('competitions.index') }}" class="inline-flex items-center gap-2 text-sm text-primary-container hover:text-primary-container/80 font-medium transition">
                    ← Back to leagues
                </a>
            </div>
        </div>
    </div>
</x-layouts::app>
