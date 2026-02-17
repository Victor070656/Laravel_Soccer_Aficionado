<x-layouts::app :title="__('Polls')">
    <div class="max-w-4xl mx-auto space-y-8 p-2 sm:p-4">
        {{-- Header --}}
        <div class="relative rounded-2xl overflow-hidden bg-gradient-to-r from-violet-600 via-purple-600 to-fuchsia-600 p-6 sm:p-8 text-white shadow-xl">
            <div class="absolute inset-0 opacity-10" style="background-image: url('https://images.unsplash.com/photo-1517649763962-0c623066013b?w=1200&q=50'); background-size: cover; background-position: center;"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/3 -translate-x-1/4"></div>
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold mb-2 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl">📊</span>
                        Polls
                    </h1>
                    <p class="text-violet-100 text-sm sm:text-base">Vote on match predictions, player picks, and fan debates.</p>
                </div>
                <button onclick="document.getElementById('create-poll').classList.toggle('hidden')" class="inline-flex items-center gap-2 rounded-xl bg-white/20 backdrop-blur-sm hover:bg-white/30 px-5 py-2.5 text-sm font-semibold text-white transition-all border border-white/20 hover:scale-105">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Create Poll
                </button>
            </div>
        </div>

        {{-- Create Poll Form --}}
        <div id="create-poll" class="hidden rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-violet-50 to-purple-50 dark:from-violet-900/10 dark:to-purple-900/10">
                <h2 class="font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center text-violet-600 text-sm">✨</span>
                    Create a New Poll
                </h2>
            </div>
            <form action="{{ route('polls.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Question</label>
                    <input type="text" name="question" required class="w-full rounded-xl border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white focus:border-green-500 focus:ring-green-500/20 text-sm" placeholder="What do you want to ask?">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Options</label>
                    <div id="poll-options" class="space-y-2">
                        <input type="text" name="options[]" placeholder="Option 1" required class="w-full rounded-xl border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white focus:border-green-500 focus:ring-green-500/20 text-sm">
                        <input type="text" name="options[]" placeholder="Option 2" required class="w-full rounded-xl border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white focus:border-green-500 focus:ring-green-500/20 text-sm">
                    </div>
                    <button type="button" onclick="addPollOption()" class="mt-2 text-sm text-violet-600 hover:text-violet-700 dark:text-violet-400 font-medium transition">+ Add option</button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Link to Match (optional)</label>
                        <select name="match_id" class="w-full rounded-xl border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white focus:border-green-500 focus:ring-green-500/20 text-sm">
                            <option value="">No match</option>
                            @foreach(\App\Models\FootballMatch::upcoming()->with('homeClub', 'awayClub')->limit(20)->get() as $match)
                            <option value="{{ $match->id }}">{{ $match->homeClub->name }} vs {{ $match->awayClub->name }} ({{ $match->kick_off->format('M d') }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Ends at</label>
                        <input type="datetime-local" name="ends_at" class="w-full rounded-xl border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white focus:border-green-500 focus:ring-green-500/20 text-sm">
                    </div>
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="rounded-xl bg-gradient-to-r from-violet-600 to-purple-500 px-6 py-2.5 text-sm font-semibold text-white hover:from-violet-500 hover:to-purple-400 transition-all shadow-md shadow-violet-600/20 hover:scale-105">Create Poll</button>
                </div>
            </form>
        </div>

        {{-- Poll Cards --}}
        <div class="space-y-5">
            @forelse($polls as $poll)
            <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('polls.show', $poll) }}" class="text-lg font-bold text-zinc-900 dark:text-white hover:text-violet-600 transition">{{ $poll->question }}</a>
                            <div class="flex items-center gap-3 mt-1.5">
                                <span class="text-xs text-zinc-400 dark:text-zinc-500">by {{ $poll->user->name }}</span>
                                @if($poll->match)
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-green-600 bg-green-50 dark:bg-green-900/20 px-2 py-0.5 rounded-full">🏟️ {{ $poll->match->homeClub->name }} vs {{ $poll->match->awayClub->name }}</span>
                                @endif
                            </div>
                        </div>
                        @if($poll->isOpen())
                        <span class="flex-shrink-0 inline-flex items-center gap-1.5 rounded-full bg-green-100 dark:bg-green-900/30 px-3 py-1 text-xs font-semibold text-green-700 dark:text-green-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            Open
                        </span>
                        @else
                        <span class="flex-shrink-0 inline-flex items-center rounded-full bg-zinc-100 dark:bg-zinc-700 px-3 py-1 text-xs font-semibold text-zinc-500 dark:text-zinc-400">Closed</span>
                        @endif
                    </div>

                    @php
                        $totalVotes = $poll->options->sum('votes_count');
                        $hasVoted = auth()->user()->hasVotedOn($poll);
                    @endphp

                    <div class="space-y-2.5">
                        @foreach($poll->options as $option)
                        @php
                            $pct = $totalVotes > 0 ? round(($option->votes_count / $totalVotes) * 100) : 0;
                        @endphp
                        @if($hasVoted || !$poll->isOpen())
                        <div class="relative rounded-xl overflow-hidden bg-zinc-100 dark:bg-zinc-700/50">
                            <div class="absolute inset-y-0 left-0 bg-gradient-to-r from-violet-200 to-purple-200 dark:from-violet-800/50 dark:to-purple-800/50 transition-all duration-700 ease-out" style="width: {{ $pct }}%"></div>
                            <div class="relative flex items-center justify-between px-4 py-3">
                                <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $option->text }}</span>
                                <span class="text-sm font-bold text-zinc-900 dark:text-white tabular-nums">{{ $pct }}%</span>
                            </div>
                        </div>
                        @else
                        <form action="{{ route('polls.vote', $poll) }}" method="POST">
                            @csrf
                            <input type="hidden" name="poll_option_id" value="{{ $option->id }}">
                            <button type="submit" class="w-full text-left rounded-xl border-2 border-zinc-200 dark:border-zinc-600 px-4 py-3 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:border-violet-500 hover:bg-violet-50 dark:hover:bg-violet-900/20 transition-all hover:scale-[1.01]">
                                {{ $option->text }}
                            </button>
                        </form>
                        @endif
                        @endforeach
                    </div>

                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-zinc-100 dark:border-zinc-700/60">
                        <span class="text-xs text-zinc-400 dark:text-zinc-500 font-medium">{{ $totalVotes }} votes</span>
                        @if($poll->ends_at)
                        <span class="text-xs text-zinc-400 dark:text-zinc-500">Ends {{ $poll->ends_at->diffForHumans() }}</span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="rounded-2xl border-2 border-dashed border-zinc-200 dark:border-zinc-700 p-12 text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                    <span class="text-4xl">📊</span>
                </div>
                <h2 class="text-xl font-bold text-zinc-700 dark:text-zinc-300 mb-2">No Polls Yet</h2>
                <p class="text-sm text-zinc-400 dark:text-zinc-500 max-w-md mx-auto">Create the first poll and start a conversation!</p>
            </div>
            @endforelse
        </div>

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
            input.className = 'w-full rounded-xl border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white focus:border-green-500 focus:ring-green-500/20 text-sm';
            document.getElementById('poll-options').appendChild(input);
        }
    </script>
</x-layouts::app>
