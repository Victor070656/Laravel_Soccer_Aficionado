<x-layouts::app :title="__('Manage Matches')">
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Match Management</h1>
            <a href="{{ route('admin.matches.create') }}" class="rounded-lg bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-700">Add Match</a>
        </div>

        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-100 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900">
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Match</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Competition</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Score</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-zinc-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                    @foreach($matches as $match)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                        <td class="px-4 py-3">
                            <span class="font-medium text-zinc-900 dark:text-white">{{ $match->homeClub->name }} vs {{ $match->awayClub->name }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-500">{{ $match->competition->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-500">{{ $match->kick_off->format('M d, Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                @if($match->status === 'live') bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300
                                @elseif($match->status === 'finished') bg-zinc-100 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-300
                                @else bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300
                                @endif">{{ ucfirst($match->status) }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm font-bold text-zinc-900 dark:text-white">
                            @if($match->status !== 'scheduled')
                            {{ $match->home_score }} - {{ $match->away_score }}
                            @else —
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('admin.matches.edit', $match) }}" class="text-sm text-green-600 hover:text-green-700">Edit</a>
                            <form action="{{ route('admin.matches.destroy', $match) }}" method="POST" class="inline" onsubmit="return confirm('Delete this match?')">
                                @csrf @method('DELETE')
                                <button class="text-sm text-red-500 hover:text-red-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>{{ $matches->links() }}</div>
    </div>
</x-layouts::app>
