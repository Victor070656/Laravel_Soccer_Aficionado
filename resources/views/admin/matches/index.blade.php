<x-layouts::app :title="__('Match Management')">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 p-8">
            <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white">⚽ Match Management</h1>
                    <p class="text-green-100 mt-1">Browse and sync matches from the football API</p>
                </div>
                <form action="{{ route('admin.matches.syncApi') }}" method="POST" class="flex items-center gap-2">
                    @csrf
                    <input type="date" name="date" value="{{ request('date', now()->format('Y-m-d')) }}" class="rounded-xl border-white/20 bg-white/10 text-white text-sm placeholder-white/50 focus:border-white/40 focus:ring-white/20">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-white/20 backdrop-blur-sm hover:bg-white/30 px-5 py-2.5 text-sm font-semibold text-white transition-all border border-white/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Sync to DB
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
        <div class="rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 px-4 py-3 text-sm text-green-700 dark:text-green-300">
            {{ session('success') }}
        </div>
        @endif

        {{-- Filters --}}
        <form action="{{ route('admin.matches.index') }}" method="GET" class="flex flex-wrap gap-3 p-4 rounded-2xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700/50">
            <select name="status" class="rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm">
                <option value="">All Status</option>
                <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="live" {{ request('status') === 'live' ? 'selected' : '' }}>Live</option>
                <option value="finished" {{ request('status') === 'finished' ? 'selected' : '' }}>Finished</option>
            </select>
            <select name="league" class="rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm">
                <option value="">All Leagues</option>
                @foreach($leagues as $league)
                <option value="{{ $league->id }}" {{ request('league') == $league->id ? 'selected' : '' }}>{{ $league->name }}</option>
                @endforeach
            </select>
            <input type="date" name="date" value="{{ request('date') }}" class="rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm">
            <button type="submit" class="rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-green-500/25 transition-all hover:from-green-500 hover:to-emerald-500">Filter</button>
            @if(request()->hasAny(['status', 'league', 'date']))
            <a href="{{ route('admin.matches.index') }}" class="rounded-xl border border-zinc-200 dark:border-zinc-600 px-4 py-2 text-sm text-zinc-500 hover:text-zinc-700 transition">Clear</a>
            @endif
        </form>

        {{-- Matches Grid --}}
        <div class="space-y-3">
            @forelse($matches as $match)
            <a href="{{ route('admin.matches.show', $match->id) }}" class="block rounded-2xl border border-zinc-200 dark:border-zinc-700/50 bg-white dark:bg-zinc-800/50 hover:border-green-300 dark:hover:border-green-600/50 hover:shadow-lg transition-all p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4 flex-1">
                        {{-- Home --}}
                        <div class="flex items-center gap-2 w-40 justify-end text-right">
                            <span class="text-sm font-semibold text-zinc-900 dark:text-white truncate">{{ $match->home_team['name'] ?? 'TBD' }}</span>
                            @if($match->home_team['logo'] ?? null)
                            <img src="{{ $match->home_team['logo'] }}" class="w-8 h-8 object-contain" alt="">
                            @endif
                        </div>

                        {{-- Score / Status --}}
                        <div class="text-center min-w-[80px]">
                            @if($match->status === 'finished')
                            <div class="text-lg font-black text-zinc-900 dark:text-white tabular-nums">{{ $match->score_display }}</div>
                            @elseif($match->status === 'live' || $match->status === 'half_time')
                            <div class="text-lg font-black text-red-600 dark:text-red-400 tabular-nums animate-pulse">{{ $match->score_display }}</div>
                            @else
                            <div class="text-xs text-zinc-400">{{ $match->date ? \Carbon\Carbon::parse($match->date)->format('H:i') : 'TBD' }}</div>
                            @endif
                            @php
                                $statusColors = [
                                    'scheduled' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300',
                                    'live' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                                    'half_time' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300',
                                    'finished' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300',
                                    'postponed' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-300',
                                    'cancelled' => 'bg-zinc-100 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300',
                                ];
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold mt-1 {{ $statusColors[$match->status] ?? 'bg-zinc-100 text-zinc-600' }}">
                                {{ ucfirst(str_replace('_', ' ', $match->status)) }}
                            </span>
                        </div>

                        {{-- Away --}}
                        <div class="flex items-center gap-2 w-40">
                            @if($match->away_team['logo'] ?? null)
                            <img src="{{ $match->away_team['logo'] }}" class="w-8 h-8 object-contain" alt="">
                            @endif
                            <span class="text-sm font-semibold text-zinc-900 dark:text-white truncate">{{ $match->away_team['name'] ?? 'TBD' }}</span>
                        </div>
                    </div>

                    <div class="text-right ml-4 hidden sm:block">
                        <p class="text-xs text-zinc-400">{{ $match->league['name'] ?? '' }}</p>
                        <p class="text-xs text-zinc-400">{{ $match->league['round'] ?? '' }}</p>
                        @if($match->date)
                        <p class="text-xs text-zinc-500">{{ \Carbon\Carbon::parse($match->date)->format('M d, Y') }}</p>
                        @endif
                    </div>
                </div>
            </a>
            @empty
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700/50 bg-white dark:bg-zinc-800/50 p-12 text-center">
                <div class="text-4xl mb-2">⚽</div>
                <p class="text-zinc-400">No matches found. Try adjusting filters or syncing from the API.</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if(($result['last_page'] ?? 1) > 1)
        <div class="flex items-center justify-center gap-2">
            @if(($result['current_page'] ?? 1) > 1)
            <a href="{{ request()->fullUrlWithQuery(['page' => ($result['current_page'] ?? 1) - 1]) }}" class="rounded-lg border border-zinc-200 dark:border-zinc-600 px-4 py-2 text-sm hover:bg-zinc-50 dark:hover:bg-zinc-700">← Prev</a>
            @endif
            <span class="text-sm text-zinc-500">Page {{ $result['current_page'] ?? 1 }} of {{ $result['last_page'] ?? 1 }}</span>
            @if($result['has_more'] ?? false)
            <a href="{{ request()->fullUrlWithQuery(['page' => ($result['current_page'] ?? 1) + 1]) }}" class="rounded-lg border border-zinc-200 dark:border-zinc-600 px-4 py-2 text-sm hover:bg-zinc-50 dark:hover:bg-zinc-700">Next →</a>
            @endif
        </div>
        @endif
    </div>
</x-layouts::app>
