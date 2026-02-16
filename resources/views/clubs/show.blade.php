<x-layouts::app :title="$club->name">
    <div class="space-y-6">
        {{-- Club Header --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-4">
                    @if($club->logo)
                    <img src="{{ $club->logo }}" alt="{{ $club->name }}" class="w-16 h-16 object-contain">
                    @else
                    <div class="w-16 h-16 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-2xl font-bold text-green-600">
                        {{ strtoupper(substr($club->code ?? $club->name, 0, 3)) }}
                    </div>
                    @endif
                    <div>
                        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $club->name }}</h1>
                        <div class="flex items-center gap-3 text-sm text-zinc-400 mt-1">
                            @if($club->country) <span>🌍 {{ $club->country }}</span> @endif
                            @if($club->venue['city'] ?? null) <span>📍 {{ $club->venue['city'] }}</span> @endif
                            @if($club->venue['name'] ?? null) <span>🏟 {{ $club->venue['name'] }}</span> @endif
                            @if($club->founded) <span>Est. {{ $club->founded }}</span> @endif
                        </div>
                        @if($club->venue['capacity'] ?? null)
                        <div class="text-sm text-zinc-400 mt-1">Capacity: {{ number_format($club->venue['capacity']) }}</div>
                        @endif
                    </div>
                </div>
            </div>
            @if($club->venue['image'] ?? null)
            <div class="mt-4 rounded-lg overflow-hidden">
                <img src="{{ $club->venue['image'] }}" alt="{{ $club->venue['name'] }}" class="w-full h-48 object-cover">
            </div>
            @endif
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            {{-- Squad --}}
            <div class="lg:col-span-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                <h3 class="font-bold text-zinc-900 dark:text-white mb-4">Squad</h3>
                @if($squad->isNotEmpty())
                <div class="space-y-1">
                    @php $positions = $squad->groupBy('position'); @endphp
                    @foreach(['Goalkeeper', 'Defender', 'Midfielder', 'Attacker'] as $position)
                        @if(isset($positions[$position]))
                        <div class="mb-4">
                            <div class="text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">{{ $position }}s</div>
                            @foreach($positions[$position] as $player)
                            <div class="flex items-center justify-between py-2 border-b border-zinc-50 dark:border-zinc-700 last:border-0">
                                <div class="flex items-center gap-3">
                                    @if($player->number)
                                    <span class="w-8 h-8 flex items-center justify-center rounded bg-zinc-100 dark:bg-zinc-700 text-xs font-bold text-zinc-600 dark:text-zinc-300">{{ $player->number }}</span>
                                    @endif
                                    <div class="flex items-center gap-2">
                                        @if($player->photo)
                                        <img src="{{ $player->photo }}" alt="" class="w-8 h-8 rounded-full object-cover">
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $player->name }}</div>
                                        </div>
                                    </div>
                                </div>
                                @if($player->age)
                                <span class="text-xs text-zinc-400">{{ $player->age }} yrs</span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @endif
                    @endforeach
                </div>
                @else
                <p class="text-sm text-zinc-400">Squad information not available.</p>
                @endif
            </div>

            <div class="space-y-4">
                {{-- Recent Results --}}
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                    <h3 class="font-bold text-zinc-900 dark:text-white mb-3">Recent Results</h3>
                    @forelse($recentMatches as $match)
                    <a href="{{ route('matches.show', $match->id) }}" class="block py-2 border-b border-zinc-100 dark:border-zinc-700 last:border-0 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 -mx-2 px-2 rounded">
                        <div class="flex justify-between text-sm">
                            <div class="flex items-center gap-1">
                                @if($match->home_team['logo'] ?? null)
                                <img src="{{ $match->home_team['logo'] }}" alt="" class="h-4 w-4 object-contain">
                                @endif
                                <span class="font-medium text-zinc-800 dark:text-zinc-200">{{ $match->home_team['name'] }}</span>
                            </div>
                            <span class="font-bold">{{ $match->score_display }}</span>
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

                {{-- Upcoming --}}
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                    <h3 class="font-bold text-zinc-900 dark:text-white mb-3">Upcoming</h3>
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
                    </a>
                    @empty
                    <p class="text-sm text-zinc-400">No upcoming matches.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('clubs.index') }}" class="text-sm text-green-600 hover:text-green-700 dark:text-green-400">← Back to all clubs</a>
        </div>
    </div>
</x-layouts::app>
