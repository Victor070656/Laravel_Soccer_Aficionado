<x-layouts::app :title="__('Manage Polls')">
    <div class="max-w-7xl mx-auto space-y-6 p-2 sm:p-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Manage Polls</h1>
                <p class="text-sm text-zinc-500 mt-1">Create, close, reopen, or delete polls.</p>
            </div>
            <a href="{{ route('admin.polls.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700 transition shadow-sm">
                + New Poll
            </a>
        </div>

        {{-- Filters --}}
        <form method="GET" class="flex gap-3 flex-wrap">
            <select name="type" class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-4 py-2 text-sm" onchange="this.form.submit()">
                <option value="">All Types</option>
                <option value="general" {{ request('type') === 'general' ? 'selected' : '' }}>General</option>
                <option value="motm" {{ request('type') === 'motm' ? 'selected' : '' }}>Man of the Match</option>
                <option value="prediction" {{ request('type') === 'prediction' ? 'selected' : '' }}>Prediction</option>
                <option value="gotw" {{ request('type') === 'gotw' ? 'selected' : '' }}>Goal of the Week</option>
            </select>
            <select name="status" class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-4 py-2 text-sm" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </form>

        {{-- Table --}}
        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-100 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900/30">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-zinc-500 uppercase">Poll</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-zinc-500 uppercase">Type</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-zinc-500 uppercase">Created By</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-zinc-500 uppercase">Votes</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-zinc-500 uppercase">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-zinc-500 uppercase">Closes</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-zinc-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700/50">
                    @forelse($polls as $poll)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/30 transition">
                        <td class="px-5 py-4">
                            <div>
                                <a href="{{ route('polls.show', $poll) }}" class="font-medium text-zinc-900 dark:text-white hover:text-green-600 transition">{{ Str::limit($poll->title, 50) }}</a>
                                @if($poll->match)
                                <p class="text-xs text-zinc-400 mt-0.5">{{ $poll->match->homeClub?->name ?? '?' }} vs {{ $poll->match->awayClub?->name ?? '?' }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 uppercase">{{ $poll->type }}</span>
                        </td>
                        <td class="px-5 py-4 text-sm text-zinc-500">{{ $poll->user?->name ?? 'Deleted' }}</td>
                        <td class="px-5 py-4 text-sm text-zinc-500 text-center font-semibold">{{ number_format($poll->votes_count) }}</td>
                        <td class="px-5 py-4 text-center">
                            @if($poll->isOpen())
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Open</span>
                            @else
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-zinc-100 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-400">Closed</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-sm text-zinc-500">{{ $poll->closes_at?->format('M d, Y H:i') ?? 'Never' }}</td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($poll->isOpen())
                                <form method="POST" action="{{ route('admin.polls.close', $poll) }}">
                                    @csrf
                                    <button type="submit" class="text-sm text-orange-500 hover:text-orange-700 font-medium">Close</button>
                                </form>
                                @else
                                <form method="POST" action="{{ route('admin.polls.reopen', $poll) }}">
                                    @csrf
                                    <button type="submit" class="text-sm text-green-500 hover:text-green-700 font-medium">Reopen</button>
                                </form>
                                @endif
                                <form method="POST" action="{{ route('admin.polls.destroy', $poll) }}" onsubmit="return confirm('Delete this poll and all its votes?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-zinc-400">No polls found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $polls->links() }}
    </div>
</x-layouts::app>
