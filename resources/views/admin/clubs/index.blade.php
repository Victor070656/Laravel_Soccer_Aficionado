<x-layouts::app :title="__('Club Management')">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 p-8">
            <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white">🛡️ Club Management</h1>
                    <p class="text-blue-100 mt-1">Browse and sync clubs from the football API</p>
                </div>
                <form action="{{ route('admin.clubs.syncLeague') }}" method="POST" class="flex items-center gap-2">
                    @csrf
                    <select name="league_id" required class="rounded-xl border-white/20 bg-white/10 text-white text-sm [&>option]:text-zinc-900">
                        <option value="">Select league</option>
                        @foreach($leagues as $league)
                        <option value="{{ $league->id }}">{{ $league->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-white/20 backdrop-blur-sm hover:bg-white/30 px-5 py-2.5 text-sm font-semibold text-white transition-all border border-white/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Sync League
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
        <div class="rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 px-4 py-3 text-sm text-green-700 dark:text-green-300">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 px-4 py-3 text-sm text-red-700 dark:text-red-300">
            {{ session('error') }}
        </div>
        @endif

        {{-- Filters --}}
        <form action="{{ route('admin.clubs.index') }}" method="GET" class="flex flex-wrap gap-3 p-4 rounded-2xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700/50">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search clubs..." class="rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm flex-1 min-w-[200px]">
            <button type="submit" class="rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-green-500/25 transition-all hover:from-green-500 hover:to-emerald-500">Search</button>
            @if(request('search'))
            <a href="{{ route('admin.clubs.index') }}" class="rounded-xl border border-zinc-200 dark:border-zinc-600 px-4 py-2 text-sm text-zinc-500 hover:text-zinc-700 transition">Clear</a>
            @endif
        </form>

        {{-- Clubs Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($apiTeams as $team)
            @php $isSynced = in_array($team->id, $localClubApiIds); @endphp
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700/50 bg-white dark:bg-zinc-800/50 p-5 hover:border-blue-300 dark:hover:border-blue-600/50 hover:shadow-lg transition-all">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        @if($team->logo)
                        <img src="{{ $team->logo }}" class="w-12 h-12 object-contain" alt="">
                        @else
                        <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-xl">🛡️</div>
                        @endif
                        <div>
                            <h3 class="font-bold text-zinc-900 dark:text-white text-sm">{{ $team->name }}</h3>
                            <p class="text-xs text-zinc-400">{{ $team->country ?? '' }}{{ $team->venue['city'] ?? '' ? ' · ' . $team->venue['city'] : '' }}</p>
                        </div>
                    </div>
                    @if($isSynced)
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300">✓ Synced</span>
                    @endif
                </div>

                <div class="mt-4 flex items-center gap-2">
                    <a href="{{ route('admin.clubs.show', $team->id) }}" class="flex-1 text-center rounded-xl bg-zinc-100 dark:bg-zinc-700/50 px-3 py-2 text-xs font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition">View</a>
                    @if(!$isSynced)
                    <form action="{{ route('admin.clubs.syncClub', $team->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-blue-600 to-indigo-500 px-3 py-2 text-xs font-semibold text-white hover:from-blue-500 hover:to-indigo-400 transition">Sync to DB</button>
                    </form>
                    @else
                    <a href="{{ route('clubs.show', $team->id) }}" class="flex-1 text-center rounded-xl bg-green-100 dark:bg-green-900/30 px-3 py-2 text-xs font-semibold text-green-700 dark:text-green-300 hover:bg-green-200 transition">Public Page</a>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-full rounded-2xl border border-zinc-200 dark:border-zinc-700/50 bg-white dark:bg-zinc-800/50 p-12 text-center">
                <div class="text-4xl mb-2">🛡️</div>
                <p class="text-zinc-400">No clubs found. Try a different search or sync a league above.</p>
            </div>
            @endforelse
        </div>
    </div>
</x-layouts::app>
