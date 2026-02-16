<x-layouts::app :title="__('Matches')">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Matches</h1>
            <a href="{{ route('matches.live') }}" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition">
                <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-300 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span></span>
                Live
            </a>
        </div>

        {{-- Filters --}}
        <form method="GET" class="flex flex-wrap gap-3">
            <select name="competition_id" class="rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm">
                <option value="">All Competitions</option>
                @foreach($competitions as $comp)
                <option value="{{ $comp->id }}" {{ request('competition_id') == $comp->id ? 'selected' : '' }}>{{ $comp->name }}</option>
                @endforeach
            </select>
            <select name="status" class="rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm">
                <option value="">All Statuses</option>
                @foreach(['scheduled', 'live', 'half_time', 'finished', 'postponed'] as $status)
                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                @endforeach
            </select>
            <input type="date" name="date" value="{{ request('date') }}" class="rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm">
            <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-700">Filter</button>
        </form>

        {{-- Match List --}}
        <div class="space-y-3">
            @forelse($matches as $match)
            <a href="{{ route('matches.show', $match) }}" class="block rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-zinc-400">{{ $match->competition->name }} · {{ $match->kick_off->format('M d, Y H:i') }}</span>
                    <span class="text-xs px-2 py-0.5 rounded-full {{ match($match->status) {
                        'live', 'half_time' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                        'finished' => 'bg-zinc-100 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300',
                        'scheduled' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                        default => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                    } }}">{{ ucfirst(str_replace('_', ' ', $match->status)) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="font-semibold text-zinc-900 dark:text-white">{{ $match->homeClub->name }}</div>
                    </div>
                    <div class="px-4 text-center">
                        <span class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $match->score_display }}</span>
                    </div>
                    <div class="flex-1 text-right">
                        <div class="font-semibold text-zinc-900 dark:text-white">{{ $match->awayClub->name }}</div>
                    </div>
                </div>
                @if($match->venue)
                <div class="text-xs text-zinc-400 mt-2">📍 {{ $match->venue }}</div>
                @endif
            </a>
            @empty
            <div class="text-center py-12 text-zinc-400">No matches found.</div>
            @endforelse
        </div>

        {{ $matches->links() }}
    </div>
</x-layouts::app>
