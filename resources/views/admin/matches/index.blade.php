<x-layouts::app :title="__('Manage Matches')">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-orange-500 via-amber-500 to-yellow-500 p-8">
            <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="flex items-center justify-between relative">
                <div>
                    <h1 class="text-3xl font-bold text-white">Match Management</h1>
                    <p class="text-amber-100 mt-1">Schedule and manage football matches</p>
                </div>
                <a href="{{ route('admin.matches.create') }}" class="rounded-xl bg-white/20 backdrop-blur-sm border border-white/30 px-5 py-2.5 text-sm font-semibold text-white hover:bg-white/30 transition-all duration-300">+ Add Match</a>
            </div>
        </div>

        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700/50 bg-white dark:bg-zinc-800/50 backdrop-blur-sm overflow-hidden shadow-xl shadow-black/5">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-100 dark:border-zinc-700/50 bg-zinc-50/50 dark:bg-zinc-900/50">
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
