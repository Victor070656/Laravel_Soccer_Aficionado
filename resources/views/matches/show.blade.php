<x-layouts::app :title="$match->homeClub->name . ' vs ' . $match->awayClub->name">
    <div class="space-y-6">
        {{-- Match Header --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
            <div class="text-center text-sm text-zinc-400 mb-4">
                {{ $match->competition->name }} · {{ $match->kick_off->format('l, M d, Y · H:i') }}
                @if($match->venue) · 📍 {{ $match->venue }} @endif
            </div>
            <div class="flex items-center justify-center gap-8">
                <div class="text-center flex-1">
                    <a href="{{ route('clubs.show', $match->homeClub) }}" class="hover:text-green-600">
                        <div class="text-xl font-bold text-zinc-900 dark:text-white">{{ $match->homeClub->name }}</div>
                        <div class="text-xs text-zinc-400">Home</div>
                    </a>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold {{ $match->isLive() ? 'text-red-600' : 'text-zinc-900 dark:text-white' }}">
                        {{ $match->score_display }}
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full mt-2 inline-block {{ match($match->status) {
                        'live', 'half_time' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                        'finished' => 'bg-zinc-100 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300',
                        default => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                    } }}">{{ ucfirst(str_replace('_', ' ', $match->status)) }}</span>
                </div>
                <div class="text-center flex-1">
                    <a href="{{ route('clubs.show', $match->awayClub) }}" class="hover:text-green-600">
                        <div class="text-xl font-bold text-zinc-900 dark:text-white">{{ $match->awayClub->name }}</div>
                        <div class="text-xs text-zinc-400">Away</div>
                    </a>
                </div>
            </div>
            @if($match->isFinished() && $match->home_score_ht !== null)
            <div class="text-center text-xs text-zinc-400 mt-3">HT: {{ $match->home_score_ht }} - {{ $match->away_score_ht }}</div>
            @endif
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            {{-- Match Events --}}
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                <h3 class="font-bold text-zinc-900 dark:text-white mb-4">Match Events</h3>
                @forelse($match->events as $event)
                <div class="flex items-center gap-3 py-2 border-b border-zinc-100 dark:border-zinc-700 last:border-0">
                    <span class="text-xs font-mono text-zinc-400 w-12">{{ $event->time_display }}</span>
                    <span>{{ $event->icon }}</span>
                    <div class="flex-1">
                        <span class="text-sm text-zinc-800 dark:text-zinc-200">{{ $event->player?->name }}</span>
                        @if($event->secondaryPlayer)
                        <span class="text-xs text-zinc-400">({{ $event->secondaryPlayer->name }})</span>
                        @endif
                        @if($event->description)
                        <div class="text-xs text-zinc-400">{{ $event->description }}</div>
                        @endif
                    </div>
                    <span class="text-xs text-zinc-400">{{ $event->club->short_name ?? $event->club->name }}</span>
                </div>
                @empty
                <p class="text-sm text-zinc-400">No events recorded yet.</p>
                @endforelse
            </div>

            {{-- Match Polls --}}
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                <h3 class="font-bold text-zinc-900 dark:text-white mb-4">Polls</h3>
                @forelse($match->polls as $poll)
                <a href="{{ route('polls.show', $poll) }}" class="block py-2 border-b border-zinc-100 dark:border-zinc-700 last:border-0 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 -mx-2 px-2 rounded">
                    <div class="text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $poll->title }}</div>
                    <div class="text-xs text-zinc-400">{{ $poll->total_votes }} votes · {{ $poll->isOpen() ? 'Open' : 'Closed' }}</div>
                </a>
                @empty
                <p class="text-sm text-zinc-400">No polls for this match.</p>
                @endforelse
            </div>
        </div>

        {{-- Additional Info --}}
        @if($match->referee || $match->attendance)
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
            <h3 class="font-bold text-zinc-900 dark:text-white mb-3">Match Info</h3>
            <div class="grid gap-2 sm:grid-cols-2 text-sm">
                @if($match->referee)
                <div><span class="text-zinc-400">Referee:</span> <span class="text-zinc-800 dark:text-zinc-200">{{ $match->referee }}</span></div>
                @endif
                @if($match->attendance)
                <div><span class="text-zinc-400">Attendance:</span> <span class="text-zinc-800 dark:text-zinc-200">{{ number_format($match->attendance) }}</span></div>
                @endif
            </div>
        </div>
        @endif
    </div>
</x-layouts::app>
