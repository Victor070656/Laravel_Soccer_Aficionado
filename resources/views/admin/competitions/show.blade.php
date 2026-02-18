<x-layouts::app :title="$competition->name . ' - Admin'">
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-amber-600 via-orange-600 to-red-600 p-8 text-white">
            <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    @if($competition->logo)
                    <img src="{{ $competition->logo }}" class="w-14 h-14 object-contain" alt="">
                    @endif
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold">{{ $competition->name }}</h1>
                        <p class="text-amber-100">{{ $competition->country ?? '' }} · Season {{ $seasonDisplay }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('competitions.show', $competition->id) }}" class="rounded-xl bg-white/20 backdrop-blur-sm px-5 py-2.5 text-sm font-semibold hover:bg-white/30 transition border border-white/20">View Public</a>
                    <a href="{{ route('admin.competitions.index') }}" class="rounded-xl bg-white/10 backdrop-blur-sm px-5 py-2.5 text-sm font-semibold hover:bg-white/20 transition border border-white/20">← Back</a>
                </div>
            </div>
        </div>

        {{-- Standings --}}
        @if($standings->count())
        <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/10 dark:to-orange-900/10">
                <h3 class="font-bold text-zinc-900 dark:text-white text-sm">📊 Standings</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-zinc-100 dark:border-zinc-700/50 bg-zinc-50/50 dark:bg-zinc-900/50 text-xs text-zinc-500 uppercase">
                            <th class="px-4 py-2 text-center w-10">#</th>
                            <th class="px-4 py-2 text-left">Team</th>
                            <th class="px-4 py-2 text-center">P</th>
                            <th class="px-4 py-2 text-center">W</th>
                            <th class="px-4 py-2 text-center">D</th>
                            <th class="px-4 py-2 text-center">L</th>
                            <th class="px-4 py-2 text-center">GF</th>
                            <th class="px-4 py-2 text-center">GA</th>
                            <th class="px-4 py-2 text-center">GD</th>
                            <th class="px-4 py-2 text-center font-bold">Pts</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700/50">
                        @foreach($standings as $row)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/30 transition text-sm">
                            <td class="px-4 py-2.5 text-center font-bold text-zinc-400">{{ $row->rank }}</td>
                            <td class="px-4 py-2.5">
                                <div class="flex items-center gap-2">
                                    @if($row->team['logo'] ?? null)
                                    <img src="{{ $row->team['logo'] }}" class="w-5 h-5 object-contain" alt="">
                                    @endif
                                    <span class="font-medium text-zinc-900 dark:text-white text-xs">{{ $row->team['name'] ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-2.5 text-center text-zinc-500">{{ $row->played }}</td>
                            <td class="px-4 py-2.5 text-center text-zinc-500">{{ $row->won }}</td>
                            <td class="px-4 py-2.5 text-center text-zinc-500">{{ $row->drawn }}</td>
                            <td class="px-4 py-2.5 text-center text-zinc-500">{{ $row->lost }}</td>
                            <td class="px-4 py-2.5 text-center text-zinc-500">{{ $row->goals_for }}</td>
                            <td class="px-4 py-2.5 text-center text-zinc-500">{{ $row->goals_against }}</td>
                            <td class="px-4 py-2.5 text-center font-medium {{ $row->goals_diff > 0 ? 'text-green-600' : ($row->goals_diff < 0 ? 'text-red-500' : 'text-zinc-400') }}">{{ $row->goals_diff > 0 ? '+' : '' }}{{ $row->goals_diff }}</td>
                            <td class="px-4 py-2.5 text-center font-bold text-zinc-900 dark:text-white">{{ $row->points }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Teams --}}
            <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10">
                    <h3 class="font-bold text-zinc-900 dark:text-white text-sm">🛡️ Teams ({{ $teams->count() }})</h3>
                </div>
                <div class="p-5 space-y-2 max-h-96 overflow-y-auto">
                    @foreach($teams as $team)
                    <a href="{{ route('admin.clubs.show', $team->id) }}" class="flex items-center gap-3 p-2 rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-700/30 transition">
                        @if($team->logo)
                        <img src="{{ $team->logo }}" class="w-8 h-8 object-contain" alt="">
                        @else
                        <div class="w-8 h-8 rounded-lg bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center text-xs">🛡️</div>
                        @endif
                        <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $team->name }}</span>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Upcoming Matches --}}
            <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/10 dark:to-emerald-900/10">
                    <h3 class="font-bold text-zinc-900 dark:text-white text-sm">📅 Upcoming Matches</h3>
                </div>
                <div class="p-5 space-y-3 max-h-96 overflow-y-auto">
                    @forelse($upcomingMatches as $match)
                    <a href="{{ route('admin.matches.show', $match->id) }}" class="flex items-center justify-between p-3 rounded-xl bg-zinc-50 dark:bg-zinc-700/30 hover:bg-zinc-100 dark:hover:bg-zinc-700/50 transition">
                        <div class="flex items-center gap-2 text-xs">
                            @if($match->home_team['logo'] ?? null)<img src="{{ $match->home_team['logo'] }}" class="w-5 h-5 object-contain" alt="">@endif
                            <span class="font-medium text-zinc-900 dark:text-white">{{ $match->home_team['name'] ?? 'TBD' }}</span>
                            <span class="text-zinc-400">vs</span>
                            <span class="font-medium text-zinc-900 dark:text-white">{{ $match->away_team['name'] ?? 'TBD' }}</span>
                            @if($match->away_team['logo'] ?? null)<img src="{{ $match->away_team['logo'] }}" class="w-5 h-5 object-contain" alt="">@endif
                        </div>
                        <span class="text-[10px] text-zinc-400">{{ $match->date ? \Carbon\Carbon::parse($match->date)->format('M d') : '' }}</span>
                    </a>
                    @empty
                    <p class="text-sm text-zinc-400 text-center py-4">No upcoming matches.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Recent Results --}}
        @if($recentResults->count())
        <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-emerald-50 to-green-50 dark:from-emerald-900/10 dark:to-green-900/10">
                <h3 class="font-bold text-zinc-900 dark:text-white text-sm">📋 Recent Results</h3>
            </div>
            <div class="p-5 space-y-2">
                @foreach($recentResults as $match)
                <a href="{{ route('admin.matches.show', $match->id) }}" class="flex items-center justify-between p-3 rounded-xl bg-zinc-50 dark:bg-zinc-700/30 hover:bg-zinc-100 dark:hover:bg-zinc-700/50 transition">
                    <div class="flex items-center gap-2 text-sm">
                        @if($match->home_team['logo'] ?? null)<img src="{{ $match->home_team['logo'] }}" class="w-5 h-5 object-contain" alt="">@endif
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $match->home_team['name'] ?? '' }}</span>
                        <span class="font-black text-zinc-700 dark:text-zinc-300 tabular-nums">{{ $match->score_display }}</span>
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $match->away_team['name'] ?? '' }}</span>
                        @if($match->away_team['logo'] ?? null)<img src="{{ $match->away_team['logo'] }}" class="w-5 h-5 object-contain" alt="">@endif
                    </div>
                    <span class="text-xs text-zinc-400">{{ $match->date ? \Carbon\Carbon::parse($match->date)->format('M d') : '' }}</span>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</x-layouts::app>
