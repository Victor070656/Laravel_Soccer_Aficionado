<x-layouts::app :title="$competition->name">
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $competition->name }}</h1>
            <div class="text-sm text-zinc-400 mt-1">{{ ucfirst($competition->type) }} · {{ $competition->country ?? 'International' }} · Season {{ $competition->season }}</div>
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
                            <td class="px-4 py-3 font-bold text-zinc-600 dark:text-zinc-300">{{ $standing->position }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('clubs.show', $standing->club) }}" class="font-medium text-zinc-900 dark:text-white hover:text-green-600">{{ $standing->club->name }}</a>
                            </td>
                            <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-300">{{ $standing->played }}</td>
                            <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-300">{{ $standing->won }}</td>
                            <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-300">{{ $standing->drawn }}</td>
                            <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-300">{{ $standing->lost }}</td>
                            <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-300">{{ $standing->goals_for }}</td>
                            <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-300">{{ $standing->goals_against }}</td>
                            <td class="px-4 py-3 text-center font-medium {{ $standing->goal_difference >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ $standing->goal_difference > 0 ? '+' : '' }}{{ $standing->goal_difference }}</td>
                            <td class="px-4 py-3 text-center font-bold text-zinc-900 dark:text-white">{{ $standing->points }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-0.5">
                                    @foreach($standing->form_array as $result)
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

        <div class="grid gap-6 lg:grid-cols-2">
            {{-- Upcoming --}}
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                <h3 class="font-bold text-zinc-900 dark:text-white mb-3">Upcoming Matches</h3>
                @forelse($upcomingMatches as $match)
                <a href="{{ route('matches.show', $match) }}" class="block py-2 border-b border-zinc-100 dark:border-zinc-700 last:border-0">
                    <div class="text-xs text-zinc-400">{{ $match->kick_off->format('M d, H:i') }}</div>
                    <div class="flex justify-between text-sm font-medium text-zinc-800 dark:text-zinc-200">
                        <span>{{ $match->homeClub->name }}</span>
                        <span class="text-zinc-400">vs</span>
                        <span>{{ $match->awayClub->name }}</span>
                    </div>
                </a>
                @empty
                <p class="text-sm text-zinc-400">No upcoming matches.</p>
                @endforelse
            </div>

            {{-- Recent Results --}}
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                <h3 class="font-bold text-zinc-900 dark:text-white mb-3">Recent Results</h3>
                @forelse($recentResults as $match)
                <a href="{{ route('matches.show', $match) }}" class="block py-2 border-b border-zinc-100 dark:border-zinc-700 last:border-0">
                    <div class="flex justify-between text-sm">
                        <span class="font-medium text-zinc-800 dark:text-zinc-200">{{ $match->homeClub->name }}</span>
                        <span class="font-bold text-zinc-900 dark:text-white">{{ $match->score_display }}</span>
                        <span class="font-medium text-zinc-800 dark:text-zinc-200">{{ $match->awayClub->name }}</span>
                    </div>
                </a>
                @empty
                <p class="text-sm text-zinc-400">No recent results.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts::app>
