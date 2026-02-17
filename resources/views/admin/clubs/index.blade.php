<x-layouts::app :title="__('Manage Clubs')">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 p-8">
            <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="flex items-center justify-between relative">
                <div>
                    <h1 class="text-3xl font-bold text-white">Club Management</h1>
                    <p class="text-green-100 mt-1">Manage all football clubs on the platform</p>
                </div>
                <a href="{{ route('admin.clubs.create') }}" class="rounded-xl bg-white/20 backdrop-blur-sm border border-white/30 px-5 py-2.5 text-sm font-semibold text-white hover:bg-white/30 transition-all duration-300">+ Add Club</a>
            </div>
        </div>

        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700/50 bg-white dark:bg-zinc-800/50 backdrop-blur-sm overflow-hidden shadow-xl shadow-black/5">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-100 dark:border-zinc-700/50 bg-zinc-50/50 dark:bg-zinc-900/50">
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Club</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Country</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Stadium</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Players</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Fans</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-zinc-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                    @foreach($clubs as $club)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                @if($club->logo)
                                <img loading="lazy" decoding="async" src="{{ asset('storage/' . $club->logo) }}" alt="" class="w-8 h-8 rounded">
                                @endif
                                <span class="font-medium text-zinc-900 dark:text-white">{{ $club->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-500">{{ $club->country }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-500">{{ $club->stadium ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-500">{{ $club->players_count }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-500">{{ $club->fans_count }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('admin.clubs.edit', $club) }}" class="text-sm text-green-600 hover:text-green-700">Edit</a>
                            <form action="{{ route('admin.clubs.destroy', $club) }}" method="POST" class="inline" onsubmit="return confirm('Delete this club?')">
                                @csrf @method('DELETE')
                                <button class="text-sm text-red-500 hover:text-red-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>{{ $clubs->links() }}</div>
    </div>
</x-layouts::app>
