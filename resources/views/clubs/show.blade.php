<x-layouts::app :title="$club->name">
    <div class="space-y-6">
        {{-- Club Header --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6" style="border-top: 4px solid {{ $club->primary_color ?? '#16a34a' }}">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-xl flex items-center justify-center text-2xl font-bold" style="background-color: {{ $club->primary_color ?? '#16a34a' }}20; color: {{ $club->primary_color ?? '#16a34a' }}">
                        {{ strtoupper(substr($club->short_name ?? $club->name, 0, 3)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $club->name }}</h1>
                        <div class="flex items-center gap-3 text-sm text-zinc-400 mt-1">
                            <span>🌍 {{ $club->country }}</span>
                            @if($club->city) <span>📍 {{ $club->city }}</span> @endif
                            @if($club->stadium) <span>🏟 {{ $club->stadium }}</span> @endif
                            @if($club->founded_year) <span>Est. {{ $club->founded_year }}</span> @endif
                        </div>
                        <div class="text-sm text-zinc-400 mt-1">{{ $club->fans_count }} fans</div>
                    </div>
                </div>
                @auth
                <form action="{{ route('clubs.favorite', $club) }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-lg border border-green-600 px-4 py-2 text-sm font-medium {{ auth()->user()->favoriteClubs->contains($club->id) ? 'bg-green-600 text-white' : 'text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20' }} transition">
                        {{ auth()->user()->favoriteClubs->contains($club->id) ? '★ Favorited' : '☆ Add to Favorites' }}
                    </button>
                </form>
                @endauth
            </div>
            @if($club->description)
            <p class="mt-4 text-sm text-zinc-600 dark:text-zinc-300">{{ $club->description }}</p>
            @endif
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            {{-- Players --}}
            <div class="lg:col-span-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                <h3 class="font-bold text-zinc-900 dark:text-white mb-4">Squad</h3>
                <div class="space-y-1">
                    @php $positions = $club->players->groupBy('position'); @endphp
                    @foreach(['Goalkeeper', 'Defender', 'Midfielder', 'Forward'] as $position)
                        @if(isset($positions[$position]))
                        <div class="mb-4">
                            <div class="text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">{{ $position }}s</div>
                            @foreach($positions[$position] as $player)
                            <div class="flex items-center justify-between py-2 border-b border-zinc-50 dark:border-zinc-700 last:border-0">
                                <div class="flex items-center gap-3">
                                    @if($player->jersey_number)
                                    <span class="w-8 h-8 flex items-center justify-center rounded bg-zinc-100 dark:bg-zinc-700 text-xs font-bold text-zinc-600 dark:text-zinc-300">{{ $player->jersey_number }}</span>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $player->name }}</div>
                                        <div class="text-xs text-zinc-400">{{ $player->nationality }}</div>
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
            </div>

            <div class="space-y-4">
                {{-- Recent Matches --}}
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                    <h3 class="font-bold text-zinc-900 dark:text-white mb-3">Recent Results</h3>
                    @forelse($recentMatches as $match)
                    <a href="{{ route('matches.show', $match) }}" class="block py-2 border-b border-zinc-100 dark:border-zinc-700 last:border-0 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 -mx-2 px-2 rounded">
                        <div class="flex justify-between text-sm">
                            <span class="font-medium text-zinc-800 dark:text-zinc-200">{{ $match->homeClub->short_name ?? $match->homeClub->name }}</span>
                            <span class="font-bold">{{ $match->score_display }}</span>
                            <span class="font-medium text-zinc-800 dark:text-zinc-200">{{ $match->awayClub->short_name ?? $match->awayClub->name }}</span>
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
                    <a href="{{ route('matches.show', $match) }}" class="block py-2 border-b border-zinc-100 dark:border-zinc-700 last:border-0">
                        <div class="text-xs text-zinc-400">{{ $match->kick_off->format('M d, H:i') }}</div>
                        <div class="flex justify-between text-sm font-medium text-zinc-800 dark:text-zinc-200">
                            <span>{{ $match->homeClub->short_name ?? $match->homeClub->name }}</span>
                            <span class="text-zinc-400">vs</span>
                            <span>{{ $match->awayClub->short_name ?? $match->awayClub->name }}</span>
                        </div>
                    </a>
                    @empty
                    <p class="text-sm text-zinc-400">No upcoming matches.</p>
                    @endforelse
                </div>

                {{-- Communities --}}
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                    <h3 class="font-bold text-zinc-900 dark:text-white mb-3">Fan Communities</h3>
                    @forelse($communities as $community)
                    <a href="{{ route('communities.show', $community) }}" class="block py-2 border-b border-zinc-100 dark:border-zinc-700 last:border-0 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 -mx-2 px-2 rounded">
                        <div class="text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $community->name }}</div>
                        <div class="text-xs text-zinc-400">{{ $community->members_count }} members</div>
                    </a>
                    @empty
                    <p class="text-sm text-zinc-400">No communities yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
