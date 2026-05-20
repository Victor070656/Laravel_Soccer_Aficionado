<x-layouts::app :title="__('Polls')">
    <div class="min-h-screen bg-surface py-6">
        <div class="relative z-10 mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="glass-card rounded-xl p-6 relative overflow-hidden">
                <div class="absolute -top-20 -right-20 h-40 w-40 rounded-full bg-primary-container/10 blur-3xl"></div>
                <div class="relative z-10 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-headline-lg text-on-surface flex items-center gap-3">
                            <span class="w-12 h-12 rounded-xl bg-primary-container/20 flex items-center justify-center text-2xl">📊</span>
                            Polls
                        </h1>
                        <p class="mt-2 text-body-md text-on-surface-variant">Vote on match predictions, player picks, and fan debates.</p>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-xl p-6 space-y-5">
                <div class="flex items-center gap-2 border-b border-outline-variant/20 pb-2">
                    <flux:icon icon="plus-circle" variant="mini" class="text-primary-container" />
                    <flux:heading size="sm" class="font-black uppercase tracking-widest text-on-surface-variant">
                        {{ __('Create Poll') }}
                    </flux:heading>
                </div>

                <form action="{{ route('polls.store') }}" method="POST" class="grid gap-5">
                    @csrf
                    <flux:input name="title" :label="__('Title')" required placeholder="e.g. Who will win the derby?" />
                    <flux:textarea name="description" :label="__('Description')" rows="2" placeholder="Add details so voters know what this poll is about..." />

                    <div class="grid gap-4 sm:grid-cols-2">
                        <flux:select name="type" :label="__('Poll Type')" required>
                            <option value="general">💬 General — Fan debate or opinion</option>
                            <option value="prediction">🔮 Prediction — Match result forecast</option>
                            <option value="motm">⭐ Man of the Match</option>
                            <option value="gotw">🥅 Goal of the Week</option>
                        </flux:select>

                        <flux:select name="match_id" :label="__('Link to Match (optional)')">
                            <option value="">{{ __('No match') }}</option>
                            @foreach (\App\Models\FootballMatch::upcoming()->with('homeClub', 'awayClub')->limit(20)->get() as $match)
                                <option value="{{ $match->id }}">{{ $match->homeClub->name }} vs {{ $match->awayClub->name }} ({{ $match->kick_off->format('M d') }})</option>
                            @endforeach
                        </flux:select>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <flux:label>{{ __('Options') }}</flux:label>
                            <div id="poll-options" class="mt-2 space-y-2">
                                <input type="text" name="options[0][label]" placeholder="Option 1" required class="w-full rounded-xl border border-outline-variant/30 bg-surface-container-high px-4 py-3 text-sm text-on-surface placeholder-on-surface-variant/60">
                                <input type="text" name="options[1][label]" placeholder="Option 2" required class="w-full rounded-xl border border-outline-variant/30 bg-surface-container-high px-4 py-3 text-sm text-on-surface placeholder-on-surface-variant/60">
                            </div>
                            <button type="button" onclick="addPollOption()" class="mt-2 text-sm font-medium text-primary-container">+ Add option</button>
                        </div>
                        <flux:input type="datetime-local" name="closes_at" :label="__('Closes at (optional)')" />
                    </div>

                    <div class="flex justify-end">
                        <flux:button type="submit" variant="primary">{{ __('Create Poll') }}</flux:button>
                    </div>
                </form>
            </div>

            <div class="space-y-4">
                @forelse($polls as $poll)
                    <div class="glass-card rounded-xl p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('polls.show', $poll) }}" class="text-label-bold text-on-surface hover:text-primary-container transition-colors text-lg">
                                    {{ $poll->title }}
                                </a>
                                @if($poll->description)
                                    <p class="mt-1 text-body-sm text-on-surface-variant line-clamp-2">{{ $poll->description }}</p>
                                @endif
                                <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-on-surface-variant">
                                    <span>by {{ $poll->user->name }}</span>
                                    <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 {{ match($poll->type) {
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
                                    @if($poll->match)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-primary-container/15 px-2 py-0.5 text-primary-container">
                                            🏟️ {{ $poll->match->homeClub->name }} vs {{ $poll->match->awayClub->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <span class="shrink-0 rounded-full px-3 py-1 text-xs font-bold {{ $poll->isOpen() ? 'bg-primary-container/20 text-primary-container' : 'bg-surface-container-high text-on-surface-variant' }}">
                                {{ $poll->isOpen() ? 'Open' : 'Closed' }}
                            </span>
                        </div>

                        @php
                            $totalVotes = $poll->options->sum('votes_count');
                            $hasVoted = auth()->user()->hasVotedOn($poll);
                        @endphp

                        <div class="mt-4 space-y-2">
                            @foreach($poll->options as $option)
                                @php
                                    $pct = $totalVotes > 0 ? round(($option->votes_count / $totalVotes) * 100) : 0;
                                @endphp
                                @if($hasVoted || ! $poll->isOpen())
                                    <div class="relative overflow-hidden rounded-xl bg-surface-container-high">
                                        <div class="absolute inset-y-0 left-0 bg-primary-container/20 transition-all duration-700" style="width: {{ $pct }}%"></div>
                                        <div class="relative flex items-center justify-between px-4 py-3">
                                            <span class="text-sm font-medium text-on-surface">{{ $option->label }}</span>
                                            <span class="text-sm font-bold text-on-surface tabular-nums">{{ $pct }}%</span>
                                        </div>
                                    </div>
                                @else
                                    <form action="{{ route('polls.vote', $poll) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="poll_option_id" value="{{ $option->id }}">
                                        <button type="submit" class="w-full rounded-xl border border-outline-variant/30 bg-surface-container-high px-4 py-3 text-left text-sm font-medium text-on-surface hover:border-primary-container/50 hover:bg-primary-container/10 transition-colors">
                                            {{ $option->label }}
                                        </button>
                                    </form>
                                @endif
                            @endforeach
                        </div>

                        <div class="mt-4 flex items-center justify-between border-t border-outline-variant/20 pt-4 text-xs text-on-surface-variant">
                            <span>{{ $totalVotes }} votes</span>
                            @if($poll->closes_at)
                                <span>{{ $poll->isOpen() ? 'Closes' : 'Closed' }} {{ $poll->closes_at->diffForHumans() }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="glass-card rounded-xl p-8 text-center">
                        <div class="text-4xl mb-4">📊</div>
                        <h2 class="text-headline-md text-on-surface mb-2">No polls yet</h2>
                        <p class="text-body-md text-on-surface-variant">Create the first poll and start a conversation.</p>
                    </div>
                @endforelse
            </div>

            <div>{{ $polls->links() }}</div>
        </div>
    </div>

    <script>
        let optionCount = 2;
        function addPollOption() {
            if (optionCount >= 10) return;
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'options[' + optionCount + '][label]';
            input.placeholder = 'Option ' + (optionCount + 1);
            input.required = true;
            input.className = 'w-full rounded-xl border border-outline-variant/30 bg-surface-container-high px-4 py-3 text-sm text-on-surface placeholder-on-surface-variant/60';
            document.getElementById('poll-options').appendChild(input);
            optionCount++;
        }
    </script>
</x-layouts::app>
