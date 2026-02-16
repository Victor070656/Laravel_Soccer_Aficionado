<x-layouts::app :title="__('Polls')">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Polls</h1>
            <button onclick="document.getElementById('create-poll').classList.toggle('hidden')" class="rounded-lg bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-700">Create Poll</button>
        </div>

        {{-- Create Poll --}}
        <div id="create-poll" class="hidden rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
            <h2 class="font-bold text-zinc-900 dark:text-white mb-4">Create a new poll</h2>
            <form action="{{ route('polls.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Question</label>
                    <input type="text" name="question" required class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Options</label>
                    <div id="poll-options" class="space-y-2">
                        <input type="text" name="options[]" placeholder="Option 1" required class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white text-sm">
                        <input type="text" name="options[]" placeholder="Option 2" required class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white text-sm">
                    </div>
                    <button type="button" onclick="addPollOption()" class="mt-2 text-sm text-green-600 hover:text-green-700">+ Add option</button>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Link to Match (optional)</label>
                        <select name="match_id" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white text-sm">
                            <option value="">No match</option>
                            @foreach(\App\Models\FootballMatch::upcoming()->with('homeClub', 'awayClub')->limit(20)->get() as $match)
                            <option value="{{ $match->id }}">{{ $match->homeClub->name }} vs {{ $match->awayClub->name }} ({{ $match->kick_off->format('M d') }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Ends at</label>
                        <input type="datetime-local" name="ends_at" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white text-sm">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="rounded-lg bg-green-600 px-6 py-2 text-sm text-white hover:bg-green-700">Create Poll</button>
                </div>
            </form>
        </div>

        {{-- Polls --}}
        @forelse($polls as $poll)
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <a href="{{ route('polls.show', $poll) }}" class="text-lg font-bold text-zinc-900 dark:text-white hover:text-green-600">{{ $poll->question }}</a>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-xs text-zinc-400">by {{ $poll->user->name }}</span>
                        @if($poll->match)
                        <span class="text-xs text-green-600">🏟️ {{ $poll->match->homeClub->name }} vs {{ $poll->match->awayClub->name }}</span>
                        @endif
                    </div>
                </div>
                @if($poll->isOpen())
                <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900 px-2 py-0.5 text-xs font-medium text-green-700 dark:text-green-300">Open</span>
                @else
                <span class="inline-flex items-center rounded-full bg-zinc-100 dark:bg-zinc-700 px-2 py-0.5 text-xs font-medium text-zinc-500">Closed</span>
                @endif
            </div>

            @php
                $totalVotes = $poll->options->sum('votes_count');
                $hasVoted = auth()->user()->hasVotedOn($poll);
            @endphp

            <div class="space-y-2">
                @foreach($poll->options as $option)
                @php
                    $pct = $totalVotes > 0 ? round(($option->votes_count / $totalVotes) * 100) : 0;
                @endphp
                @if($hasVoted || !$poll->isOpen())
                <div class="relative rounded-lg overflow-hidden bg-zinc-100 dark:bg-zinc-700">
                    <div class="absolute inset-y-0 left-0 bg-green-200 dark:bg-green-800" style="width: {{ $pct }}%"></div>
                    <div class="relative flex items-center justify-between px-4 py-2">
                        <span class="text-sm text-zinc-900 dark:text-white">{{ $option->text }}</span>
                        <span class="text-sm font-bold text-zinc-900 dark:text-white">{{ $pct }}%</span>
                    </div>
                </div>
                @else
                <form action="{{ route('polls.vote', $poll) }}" method="POST">
                    @csrf
                    <input type="hidden" name="poll_option_id" value="{{ $option->id }}">
                    <button type="submit" class="w-full text-left rounded-lg border border-zinc-300 dark:border-zinc-600 px-4 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:border-green-500 hover:bg-green-50 dark:hover:bg-green-900/20 transition">
                        {{ $option->text }}
                    </button>
                </form>
                @endif
                @endforeach
            </div>

            <div class="flex items-center justify-between mt-3 pt-3 border-t border-zinc-100 dark:border-zinc-700">
                <span class="text-xs text-zinc-400">{{ $totalVotes }} votes</span>
                @if($poll->ends_at)
                <span class="text-xs text-zinc-400">Ends {{ $poll->ends_at->diffForHumans() }}</span>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-12 text-zinc-400">
            <p class="text-lg">No polls yet</p>
            <p class="text-sm mt-1">Create the first one!</p>
        </div>
        @endforelse

        <div>{{ $polls->links() }}</div>
    </div>

    <script>
        let optionCount = 2;
        function addPollOption() {
            optionCount++;
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'options[]';
            input.placeholder = 'Option ' + optionCount;
            input.className = 'w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white text-sm';
            document.getElementById('poll-options').appendChild(input);
        }
    </script>
</x-layouts::app>
