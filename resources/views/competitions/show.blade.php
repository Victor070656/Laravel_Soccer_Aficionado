<x-layouts::app :title="$competition->name">
    <div class="space-y-6">
        <div class="flex items-center gap-4">
            @if($competition->logo)
            <img src="{{ $competition->logo }}" alt="{{ $competition->name }}" class="w-12 h-12 object-contain">
            @endif
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $competition->name }}</h1>
                <div class="flex items-center gap-2 text-sm text-zinc-400 mt-1">
                    @if($competition->country_flag ?? null)
                    <img src="{{ $competition->country_flag }}" alt="" class="h-3 w-4 object-contain">
                    @endif
                    {{ ucfirst($competition->type) }} · {{ $competition->country ?? 'International' }} · Season {{ $seasonDisplay }}
                </div>
            </div>
        </div>

        {{-- Standings --}}
        @if($standings->isNotEmpty())
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 overflow-hidden">
            <div class="p-4 border-b border-zinc-200 dark:border-zinc-700">
                <h3 class="font-bold text-zinc-900 dark:text-white">Standings</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-zinc-50 dark:bg-zinc-900 text-xs text-zinc-500 dark:text-zinc-400 uppercase">
                        <tr>
                            <th class="px-4 py-3 text-left">#</th>
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
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                        @foreach($standings as $standing)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                            <td class="px-4 py-3 font-bold text-zinc-600 dark:text-zinc-300">{{ $standing->rank }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('clubs.show', $standing->team['id']) }}" class="flex items-center gap-2 font-medium text-zinc-900 dark:text-white hover:text-green-600">
                                    @if($standing->team['logo'] ?? null)
                                    <img src="{{ $standing->team['logo'] }}" alt="" class="h-5 w-5 object-contain">
                                    @endif
                                    {{ $standing->team['name'] }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-300">{{ $standing->played }}</td>
                            <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-300">{{ $standing->won }}</td>
                            <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-300">{{ $standing->drawn }}</td>
                            <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-300">{{ $standing->lost }}</td>
                            <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-300">{{ $standing->goals_for }}</td>
                            <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-300">{{ $standing->goals_against }}</td>
                            <td class="px-4 py-3 text-center font-medium {{ $standing->goals_diff >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ $standing->goals_diff > 0 ? '+' : '' }}{{ $standing->goals_diff }}</td>
                            <td class="px-4 py-3 text-center font-bold text-zinc-900 dark:text-white">{{ $standing->points }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-0.5">
                                    @foreach(str_split($standing->form) as $result)
                                    <span class="w-5 h-5 rounded text-[10px] flex items-center justify-center font-bold {{ match($result) { 'W' => 'bg-green-100 text-green-700', 'D' => 'bg-yellow-100 text-yellow-700', 'L' => 'bg-red-100 text-red-700', default => 'bg-zinc-100 text-zinc-500' } }}">{{ $result }}</span>
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
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
            <h3 class="font-bold text-zinc-900 dark:text-white mb-4">Teams</h3>
            <div class="grid gap-3 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @foreach($teams as $team)
                <a href="{{ route('clubs.show', $team->id) }}" class="flex items-center gap-2 p-2 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition">
                    @if($team->logo)
                    <img src="{{ $team->logo }}" alt="" class="h-8 w-8 object-contain">
                    @endif
                    <span class="text-sm font-medium text-zinc-800 dark:text-zinc-200 hover:text-green-600">{{ $team->name }}</span>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-2">
            {{-- Upcoming --}}
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                <h3 class="font-bold text-zinc-900 dark:text-white mb-3">Upcoming Matches</h3>
                @forelse($upcomingMatches as $match)
                <a href="{{ route('matches.show', $match->id) }}" class="block py-2 border-b border-zinc-100 dark:border-zinc-700 last:border-0">
                    <div class="text-xs text-zinc-400">{{ \Carbon\Carbon::parse($match->date)->format('M d, H:i') }}</div>
                    <div class="flex justify-between text-sm font-medium text-zinc-800 dark:text-zinc-200 mt-1">
                        <div class="flex items-center gap-1">
                            @if($match->home_team['logo'] ?? null)
                            <img src="{{ $match->home_team['logo'] }}" alt="" class="h-4 w-4 object-contain">
                            @endif
                            <span>{{ $match->home_team['name'] }}</span>
                        </div>
                        <span class="text-zinc-400">vs</span>
                        <div class="flex items-center gap-1">
                            <span>{{ $match->away_team['name'] }}</span>
                            @if($match->away_team['logo'] ?? null)
                            <img src="{{ $match->away_team['logo'] }}" alt="" class="h-4 w-4 object-contain">
                            @endif
                        </div>
                    </div>
                    @if($match->league['round'] ?? null)
                    <div class="text-xs text-zinc-400 mt-0.5">{{ $match->league['round'] }}</div>
                    @endif
                </a>
                @empty
                <p class="text-sm text-zinc-400">No upcoming matches.</p>
                @endforelse
            </div>

            {{-- Recent Results --}}
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                <h3 class="font-bold text-zinc-900 dark:text-white mb-3">Recent Results</h3>
                @forelse($recentResults as $match)
                <a href="{{ route('matches.show', $match->id) }}" class="block py-2 border-b border-zinc-100 dark:border-zinc-700 last:border-0">
                    <div class="flex justify-between text-sm">
                        <div class="flex items-center gap-1">
                            @if($match->home_team['logo'] ?? null)
                            <img src="{{ $match->home_team['logo'] }}" alt="" class="h-4 w-4 object-contain">
                            @endif
                            <span class="font-medium text-zinc-800 dark:text-zinc-200">{{ $match->home_team['name'] }}</span>
                        </div>
                        <span class="font-bold text-zinc-900 dark:text-white">{{ $match->score_display }}</span>
                        <div class="flex items-center gap-1">
                            <span class="font-medium text-zinc-800 dark:text-zinc-200">{{ $match->away_team['name'] }}</span>
                            @if($match->away_team['logo'] ?? null)
                            <img src="{{ $match->away_team['logo'] }}" alt="" class="h-4 w-4 object-contain">
                            @endif
                        </div>
                    </div>
                </a>
                @empty
                <p class="text-sm text-zinc-400">No recent results.</p>
                @endforelse
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('competitions.index') }}" class="text-sm text-green-600 hover:text-green-700 dark:text-green-400">← Back to all competitions</a>
        </div>
    </div>
</x-layouts::app>
