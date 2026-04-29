<x-layouts::app :title="$poll->title">
    <div class="max-w-3xl mx-auto space-y-6 p-2 sm:p-4">
        {{-- Poll Card --}}
        <div class="rounded-2xl border border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container shadow-sm overflow-hidden glass-edge">
            {{-- Poll Header --}}
            <div class="px-6 py-5 border-b border-outline-variant/20 dark:border-outline-variant/30 bg-gradient-to-r from-primary/20 to-primary/10 dark:from-primary/25 dark:to-primary/15">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full
                                @switch($poll->type)
                                    @case('motm') text-tertiary bg-tertiary/20 dark:bg-tertiary/25 dark:text-tertiary @break
                                    @case('prediction') text-secondary bg-secondary/20 dark:bg-secondary/25 dark:text-secondary @break
                                    @case('gotw') text-secondary bg-secondary/20 dark:bg-secondary/25 dark:text-secondary @break
                                    @default text-primary bg-primary/20 dark:bg-primary/25 dark:text-primary
                                @endswitch
                            ">
                                @switch($poll->type)
                                    @case('motm') ⭐ Man of the Match @break
                                    @case('prediction') 🔮 Prediction @break
                                    @case('gotw') 🥅 Goal of the Week @break
                                    @default 💬 General
                                @endswitch
                            </span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-bold text-on-surface">{{ $poll->title }}</h1>
                        @if($poll->description)
                        <p class="text-sm text-on-surface-variant mt-1.5 leading-relaxed">{{ $poll->description }}</p>
                        @endif
                        <div class="flex items-center gap-3 mt-2">
                            <a href="{{ route('profiles.show', $poll->user) }}" class="inline-flex items-center gap-1.5 text-sm text-on-surface-variant dark:text-on-surface-variant hover:text-primary transition">
                                @if($poll->user->avatar)
                                <img src="{{ $poll->user->avatar_url }}" alt="{{ $poll->user->name }}" class="w-5 h-5 rounded-full object-cover">
                                @else
                                <div class="w-5 h-5 rounded-full bg-gradient-to-br from-primary to-primary/60 flex items-center justify-center text-on-primary text-[10px] font-bold">{{ strtoupper(substr($poll->user->name, 0, 1)) }}</div>
                                @endif
                                {{ $poll->user->name }}
                            </a>
                            <span class="text-xs text-zinc-400 dark:text-zinc-500">{{ $poll->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @if($poll->isOpen())
                    <span class="shrink-0 inline-flex items-center gap-1.5 rounded-full bg-primary/20 dark:bg-primary/25 px-3 py-1.5 text-xs font-semibold text-primary dark:text-primary">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                        Open
                    </span>
                    @else
                    <span class="shrink-0 inline-flex items-center rounded-full bg-surface-container-high dark:bg-surface-container-high px-3 py-1.5 text-xs font-semibold text-on-surface-variant dark:text-on-surface-variant">Closed</span>
                    @endif
                </div>
            </div>

            <div class="p-6">
                {{-- Linked Match --}}
                @if($poll->match)
                <a href="{{ route('matches.show', $poll->match) }}" class="flex items-center gap-3 mb-6 rounded-xl bg-surface-container-low dark:bg-surface-container-low p-4 hover:bg-surface-container dark:hover:bg-surface-container transition border border-outline-variant/20 dark:border-outline-variant/30">
                    <span class="w-10 h-10 rounded-lg bg-primary/20 dark:bg-primary/25 flex items-center justify-center text-lg">🏟️</span>
                    <div class="flex-1">
                        <span class="text-sm font-semibold text-on-surface dark:text-on-surface">{{ $poll->match->homeClub->name }} vs {{ $poll->match->awayClub->name }}</span>
                        <div class="text-xs text-on-surface-variant dark:text-on-surface-variant">{{ $poll->match->kick_off->format('M d, Y H:i') }}</div>
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
                    <div class="relative rounded-xl overflow-hidden {{ $isWinning ? 'ring-2 ring-primary dark:ring-primary' : '' }}">
                        <div class="absolute inset-0 bg-surface-container-high dark:bg-surface-container-high"></div>
                        <div class="absolute inset-y-0 left-0 {{ $isWinning ? 'bg-gradient-to-r from-primary/60 to-primary/40 dark:from-primary/50 dark:to-primary/30' : 'bg-gradient-to-r from-outline-variant/40 to-outline-variant/30 dark:from-outline-variant/30 dark:to-outline-variant/20' }} transition-all duration-1000 ease-out" style="width: {{ $pct }}%"></div>
                        <div class="relative flex items-center justify-between px-5 py-4">
                            <div class="flex items-center gap-2">
                                @if($isWinning)
                                <span class="text-sm">👑</span>
                                @endif
                                <span class="text-sm font-semibold text-on-surface dark:text-on-surface">{{ $option->label }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs text-on-surface-variant dark:text-on-surface-variant">{{ $option->votes_count }} votes</span>
                                <span class="text-sm font-black {{ $isWinning ? 'text-primary dark:text-primary' : 'text-on-surface dark:text-on-surface' }} tabular-nums">{{ $pct }}%</span>
                            </div>
                        </div>
                    </div>
                    @else
                    <form action="{{ route('polls.vote', $poll) }}" method="POST">
                        @csrf
                        <input type="hidden" name="poll_option_id" value="{{ $option->id }}">
                        <button type="submit" class="w-full text-left rounded-xl border-2 border-outline-variant/20 dark:border-outline-variant/30 px-5 py-4 text-sm font-semibold text-on-surface dark:text-on-surface hover:border-primary hover:bg-primary/10 dark:hover:bg-primary/15 transition-all hover:scale-[1.01] hover:shadow-md">
                            {{ $option->label }}
                        </button>
                    </form>
                    @endif
                    @endforeach
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-between mt-6 pt-5 border-t border-outline-variant/20 dark:border-outline-variant/30">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-primary/20 dark:bg-primary/25 flex items-center justify-center text-primary text-xs">📊</span>
                        <span class="text-sm font-medium text-on-surface dark:text-on-surface">{{ $totalVotes }} total votes</span>
                    </div>
                    @if($poll->closes_at)
                    <span class="text-sm text-on-surface-variant dark:text-on-surface-variant">{{ $poll->isOpen() ? 'Closes' : 'Closed' }} {{ $poll->closes_at->diffForHumans() }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Back link --}}
        <div class="text-center pb-4">
            <a href="{{ route('polls.index') }}" class="inline-flex items-center gap-2 text-sm text-primary hover:text-primary/80 dark:text-primary font-medium transition hover:-translate-x-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to all polls
            </a>
        </div>
    </div>
</x-layouts::app>
