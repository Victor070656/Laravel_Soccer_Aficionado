<x-layouts::app :title="$poll->question">
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h1 class="text-xl font-bold text-zinc-900 dark:text-white">{{ $poll->question }}</h1>
                    <div class="flex items-center gap-3 mt-2">
                        <a href="{{ route('profiles.show', $poll->user) }}" class="text-sm text-zinc-500 hover:text-green-600">{{ $poll->user->name }}</a>
                        <span class="text-xs text-zinc-400">{{ $poll->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @if($poll->isOpen())
                <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900 px-3 py-1 text-xs font-medium text-green-700 dark:text-green-300">Open</span>
                @else
                <span class="inline-flex items-center rounded-full bg-zinc-100 dark:bg-zinc-700 px-3 py-1 text-xs font-medium text-zinc-500">Closed</span>
                @endif
            </div>

            @if($poll->match)
            <a href="{{ route('matches.show', $poll->match) }}" class="flex items-center gap-2 mb-4 rounded-lg bg-zinc-50 dark:bg-zinc-900 p-3 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition">
                <span class="text-lg">🏟️</span>
                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $poll->match->homeClub->name }} vs {{ $poll->match->awayClub->name }}</span>
                <span class="text-xs text-zinc-400 ml-auto">{{ $poll->match->kick_off->format('M d, Y H:i') }}</span>
            </a>
            @endif

            @php
                $totalVotes = $poll->options->sum('votes_count');
                $hasVoted = auth()->user()->hasVotedOn($poll);
            @endphp

            <div class="space-y-3">
                @foreach($poll->options as $option)
                @php
                    $pct = $totalVotes > 0 ? round(($option->votes_count / $totalVotes) * 100) : 0;
                @endphp

                @if($hasVoted || !$poll->isOpen())
                <div class="relative rounded-lg overflow-hidden bg-zinc-100 dark:bg-zinc-700">
                    <div class="absolute inset-y-0 left-0 bg-green-200 dark:bg-green-800 transition-all duration-500" style="width: {{ $pct }}%"></div>
                    <div class="relative flex items-center justify-between px-4 py-3">
                        <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $option->text }}</span>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-zinc-500">{{ $option->votes_count }} votes</span>
                            <span class="text-sm font-bold text-zinc-900 dark:text-white">{{ $pct }}%</span>
                        </div>
                    </div>
                </div>
                @else
                <form action="{{ route('polls.vote', $poll) }}" method="POST">
                    @csrf
                    <input type="hidden" name="poll_option_id" value="{{ $option->id }}">
                    <button type="submit" class="w-full text-left rounded-lg border-2 border-zinc-200 dark:border-zinc-600 px-4 py-3 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:border-green-500 hover:bg-green-50 dark:hover:bg-green-900/20 transition">
                        {{ $option->text }}
                    </button>
                </form>
                @endif
                @endforeach
            </div>

            <div class="flex items-center justify-between mt-4 pt-4 border-t border-zinc-100 dark:border-zinc-700">
                <span class="text-sm text-zinc-500">{{ $totalVotes }} total votes</span>
                @if($poll->ends_at)
                <span class="text-sm text-zinc-500">{{ $poll->isOpen() ? 'Ends' : 'Ended' }} {{ $poll->ends_at->diffForHumans() }}</span>
                @endif
            </div>
        </div>
    </div>
</x-layouts::app>
