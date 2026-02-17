<x-layouts::app :title="$poll->question">
    <div class="max-w-3xl mx-auto space-y-6 p-2 sm:p-4">
        {{-- Poll Card --}}
        <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
            {{-- Poll Header --}}
            <div class="px-6 py-5 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-violet-50 to-purple-50 dark:from-violet-900/10 dark:to-purple-900/10">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <h1 class="text-xl sm:text-2xl font-bold text-zinc-900 dark:text-white">{{ $poll->question }}</h1>
                        <div class="flex items-center gap-3 mt-2">
                            <a href="{{ route('profiles.show', $poll->user) }}" class="inline-flex items-center gap-1.5 text-sm text-zinc-500 dark:text-zinc-400 hover:text-green-600 transition">
                                <div class="w-5 h-5 rounded-full bg-gradient-to-br from-violet-400 to-purple-500 flex items-center justify-center text-white text-[10px] font-bold">{{ strtoupper(substr($poll->user->name, 0, 1)) }}</div>
                                {{ $poll->user->name }}
                            </a>
                            <span class="text-xs text-zinc-400 dark:text-zinc-500">{{ $poll->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @if($poll->isOpen())
                    <span class="flex-shrink-0 inline-flex items-center gap-1.5 rounded-full bg-green-100 dark:bg-green-900/30 px-3 py-1.5 text-xs font-semibold text-green-700 dark:text-green-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                        Open
                    </span>
                    @else
                    <span class="flex-shrink-0 inline-flex items-center rounded-full bg-zinc-100 dark:bg-zinc-700 px-3 py-1.5 text-xs font-semibold text-zinc-500 dark:text-zinc-400">Closed</span>
                    @endif
                </div>
            </div>

            <div class="p-6">
                {{-- Linked Match --}}
                @if($poll->match)
                <a href="{{ route('matches.show', $poll->match) }}" class="flex items-center gap-3 mb-6 rounded-xl bg-zinc-50 dark:bg-zinc-900/40 p-4 hover:bg-zinc-100 dark:hover:bg-zinc-700/40 transition border border-zinc-200/60 dark:border-zinc-700/40">
                    <span class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-lg">🏟️</span>
                    <div class="flex-1">
                        <span class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">{{ $poll->match->homeClub->name }} vs {{ $poll->match->awayClub->name }}</span>
                        <div class="text-xs text-zinc-400 dark:text-zinc-500">{{ $poll->match->kick_off->format('M d, Y H:i') }}</div>
                    </div>
                    <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                @endif

                @php
                    $totalVotes = $poll->options->sum('votes_count');
                    $hasVoted = auth()->user()->hasVotedOn($poll);
                    $maxVotes = $poll->options->max('votes_count');
                @endphp

                {{-- Vote Options --}}
                <div class="space-y-3">
                    @foreach($poll->options as $option)
                    @php
                        $pct = $totalVotes > 0 ? round(($option->votes_count / $totalVotes) * 100) : 0;
                        $isWinning = $option->votes_count === $maxVotes && $totalVotes > 0;
                    @endphp

                    @if($hasVoted || !$poll->isOpen())
                    <div class="relative rounded-xl overflow-hidden {{ $isWinning ? 'ring-2 ring-violet-400 dark:ring-violet-500' : '' }}">
                        <div class="absolute inset-0 bg-zinc-100 dark:bg-zinc-700/50"></div>
                        <div class="absolute inset-y-0 left-0 {{ $isWinning ? 'bg-gradient-to-r from-violet-300 to-purple-300 dark:from-violet-700/60 dark:to-purple-700/60' : 'bg-gradient-to-r from-zinc-200 to-zinc-200 dark:from-zinc-600/50 dark:to-zinc-600/50' }} transition-all duration-1000 ease-out" style="width: {{ $pct }}%"></div>
                        <div class="relative flex items-center justify-between px-5 py-4">
                            <div class="flex items-center gap-2">
                                @if($isWinning)
                                <span class="text-sm">👑</span>
                                @endif
                                <span class="text-sm font-semibold text-zinc-900 dark:text-white">{{ $option->text }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ $option->votes_count }} votes</span>
                                <span class="text-sm font-black {{ $isWinning ? 'text-violet-600 dark:text-violet-400' : 'text-zinc-900 dark:text-white' }} tabular-nums">{{ $pct }}%</span>
                            </div>
                        </div>
                    </div>
                    @else
                    <form action="{{ route('polls.vote', $poll) }}" method="POST">
                        @csrf
                        <input type="hidden" name="poll_option_id" value="{{ $option->id }}">
                        <button type="submit" class="w-full text-left rounded-xl border-2 border-zinc-200 dark:border-zinc-600 px-5 py-4 text-sm font-semibold text-zinc-700 dark:text-zinc-300 hover:border-violet-500 hover:bg-violet-50 dark:hover:bg-violet-900/20 transition-all hover:scale-[1.01] hover:shadow-md">
                            {{ $option->text }}
                        </button>
                    </form>
                    @endif
                    @endforeach
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-between mt-6 pt-5 border-t border-zinc-100 dark:border-zinc-700/60">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center text-violet-600 text-xs">📊</span>
                        <span class="text-sm font-medium text-zinc-600 dark:text-zinc-300">{{ $totalVotes }} total votes</span>
                    </div>
                    @if($poll->ends_at)
                    <span class="text-sm text-zinc-500 dark:text-zinc-400">{{ $poll->isOpen() ? 'Ends' : 'Ended' }} {{ $poll->ends_at->diffForHumans() }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Back link --}}
        <div class="text-center pb-4">
            <a href="{{ route('polls.index') }}" class="inline-flex items-center gap-2 text-sm text-green-600 hover:text-green-700 dark:text-green-400 font-medium transition hover:-translate-x-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to all polls
            </a>
        </div>
    </div>
</x-layouts::app>
