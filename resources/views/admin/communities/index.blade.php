<x-layouts::app :title="__('Manage Communities')">
    <div class="max-w-7xl mx-auto space-y-6 p-2 sm:p-4">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Manage Communities</h1>
            <p class="text-sm text-zinc-500 mt-1">Oversee fan communities, activate or deactivate them.</p>
        </div>

        {{-- Filters --}}
        <form method="GET" class="flex gap-3 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search communities..." class="flex-1 min-w-[200px] rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-4 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent" />
            <label class="inline-flex items-center gap-2 cursor-pointer rounded-xl border border-zinc-200 dark:border-zinc-700 px-4 py-2 text-sm {{ request('inactive') ? 'bg-red-50 dark:bg-red-900/20 border-red-300 dark:border-red-700' : '' }}">
                <input type="checkbox" name="inactive" value="1" {{ request('inactive') ? 'checked' : '' }} onchange="this.form.submit()">
                <span class="text-zinc-600 dark:text-zinc-400">Inactive only</span>
            </label>
            <button type="submit" class="rounded-xl bg-zinc-100 dark:bg-zinc-700 px-4 py-2 text-sm font-medium hover:bg-zinc-200 dark:hover:bg-zinc-600 transition">Filter</button>
        </form>

        {{-- Table --}}
        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-100 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900/30">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-zinc-500 uppercase">Community</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-zinc-500 uppercase">Club</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-zinc-500 uppercase">Creator</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-zinc-500 uppercase">Members</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-zinc-500 uppercase">Status</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-zinc-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700/50">
                    @forelse($communities as $community)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/30 transition">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                @if($community->avatar)
                                <img src="{{ asset('storage/' . $community->avatar) }}" class="w-8 h-8 rounded-full object-cover" alt="">
                                @else
                                <div class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-sm">🌍</div>
                                @endif
                                <div>
                                    <span class="font-medium text-zinc-900 dark:text-white">{{ $community->name }}</span>
                                    <p class="text-xs text-zinc-400">{{ Str::limit($community->description, 60) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-sm text-zinc-500">{{ $community->club?->name ?? '—' }}</td>
                        <td class="px-5 py-4 text-sm text-zinc-500">{{ $community->creator?->name ?? 'Deleted' }}</td>
                        <td class="px-5 py-4 text-sm text-zinc-500 text-center font-semibold">{{ number_format($community->members_count) }}</td>
                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $community->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                {{ $community->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <form method="POST" action="{{ route('admin.communities.toggleActive', $community) }}">
                                    @csrf
                                    <button type="submit" class="text-sm {{ $community->is_active ? 'text-orange-500 hover:text-orange-700' : 'text-green-500 hover:text-green-700' }} font-medium">
                                        {{ $community->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.communities.destroy', $community) }}" onsubmit="return confirm('Delete this community and all its posts? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-zinc-400">No communities found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $communities->links() }}
    </div>
</x-layouts::app>
