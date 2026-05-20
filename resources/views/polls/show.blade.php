<x-layouts::app :title="$poll->title">
    <div class="min-h-screen bg-surface py-6">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="glass-card rounded-xl p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold {{ match($poll->type) {
                            'motm' => 'bg-tertiary/15 text-tertiary',
                            'prediction' => 'bg-secondary/15 text-secondary',
                            'gotw' => 'bg-primary-container/20 text-primary-container',
                            default => 'bg-surface-container-high text-on-surface-variant',
                        } }}">
                            @switch($poll->type)
                                @case('motm') ⭐ Man of the Match @break
                                @case('prediction') 🔮 Prediction @break
                                @case('gotw') 🥅 Goal of the Week @break
                                @default 💬 General
                            @endswitch
                        </span>
                        <h1 class="mt-3 text-headline-lg text-on-surface">{{ $poll->title }}</h1>
                        @if($poll->description)
                            <p class="mt-2 text-body-md text-on-surface-variant">{{ $poll->description }}</p>
                        @endif
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-bold {{ $poll->isOpen() ? 'bg-primary-container/20 text-primary-container' : 'bg-surface-container-high text-on-surface-variant' }}">
                        {{ $poll->isOpen() ? 'Open' : 'Closed' }}
                    </span>
                </div>

                <div class="mt-4 flex flex-wrap items-center gap-2 text-xs text-on-surface-variant">
                    <span>by {{ $poll->user->name }}</span>
                    <span>{{ $poll->created_at->diffForHumans() }}</span>
                </div>

                @if($poll->match)
                    <a href="{{ route('matches.show', $poll->match) }}" class="mt-6 flex items-center gap-3 rounded-xl bg-surface-container-high/60 p-4 hover:bg-surface-container-high transition-colors">
                        <span class="w-10 h-10 rounded-lg bg-primary-container/20 flex items-center justify-center">🏟️</span>
                        <div>
                            <div class="text-sm font-semibold text-on-surface">{{ $poll->match->homeClub->name }} vs {{ $poll->match->awayClub->name }}</div>
                            <div class="text-xs text-on-surface-variant">{{ $poll->match->kick_off->format('M d, Y H:i') }}</div>
                        </div>
                    </a>
                @endif

                @php
                    $totalVotes = $poll->options->sum('votes_count');
                    $hasVoted = auth()->user()->hasVotedOn($poll);
                    $maxVotes = $poll->options->max('votes_count');
                @endphp

                <div class="mt-6 space-y-3">
                    @foreach($poll->options as $option)
                        @php
                            $pct = $totalVotes > 0 ? round(($option->votes_count / $totalVotes) * 100) : 0;
                            $isWinning = $option->votes_count === $maxVotes && $totalVotes > 0;
                        @endphp

                        @if($hasVoted || ! $poll->isOpen())
                            <div class="relative overflow-hidden rounded-xl {{ $isWinning ? 'ring-2 ring-primary-container/50' : '' }}">
                                <div class="absolute inset-0 bg-surface-container-high"></div>
                                <div class="absolute inset-y-0 left-0 bg-primary-container/20 transition-all duration-1000" style="width: {{ $pct }}%"></div>
                                <div class="relative flex items-center justify-between px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        @if($isWinning)
                                            <span>👑</span>
                                        @endif
                                        <span class="text-sm font-semibold text-on-surface">{{ $option->label }}</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-xs text-on-surface-variant">
                                        <span>{{ $option->votes_count }} votes</span>
                                        <span class="text-sm font-black text-on-surface tabular-nums">{{ $pct }}%</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <form action="{{ route('polls.vote', $poll) }}" method="POST">
                                @csrf
                                <input type="hidden" name="poll_option_id" value="{{ $option->id }}">
                                <button type="submit" class="w-full rounded-xl border border-outline-variant/30 bg-surface-container-high px-5 py-4 text-left text-sm font-semibold text-on-surface hover:border-primary-container/50 hover:bg-primary-container/10 transition-colors">
                                    {{ $option->label }}
                                </button>
                            </form>
                        @endif
                    @endforeach
                </div>

                <div class="mt-6 flex items-center justify-between border-t border-outline-variant/20 pt-5 text-sm text-on-surface-variant">
                    <span>{{ $totalVotes }} total votes</span>
                    @if($poll->closes_at)
                        <span>{{ $poll->isOpen() ? 'Closes' : 'Closed' }} {{ $poll->closes_at->diffForHumans() }}</span>
                    @endif
                </div>
            </div>

            <div class="text-center pb-4">
                <a href="{{ route('polls.index') }}" class="inline-flex items-center gap-2 text-sm text-primary-container hover:text-primary-container/80 font-medium transition">
                    ← Back to polls
                </a>
            </div>
        </div>
    </div>
</x-layouts::app>
