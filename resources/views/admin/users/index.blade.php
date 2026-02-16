<x-layouts::app :title="__('Manage Users')">
    <div class="max-w-6xl mx-auto space-y-6">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">User Management</h1>

        {{-- Filters --}}
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm">
            <select name="role" class="rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm">
                <option value="">All Roles</option>
                @foreach($roles ?? [] as $role)
                <option value="{{ $role->slug }}" {{ request('role') === $role->slug ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
            <select name="status" class="rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Banned</option>
            </select>
            <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-700">Filter</button>
        </form>

        {{-- Users Table --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-100 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900">
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase">User</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Roles</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Joined</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-zinc-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                    @foreach($users as $user)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.users.show', $user) }}" class="flex items-center gap-2 hover:text-green-600">
                                <div class="w-8 h-8 rounded-full bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center text-xs font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                <span class="font-medium text-zinc-900 dark:text-white">{{ $user->name }}</span>
                            </a>
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-500">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            @foreach($user->roles as $role)
                            <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900 px-2 py-0.5 text-xs text-blue-700 dark:text-blue-300">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td class="px-4 py-3">
                            @if($user->is_banned)
                            <span class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900 px-2 py-0.5 text-xs text-red-700 dark:text-red-300">Banned</span>
                            @else
                            <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900 px-2 py-0.5 text-xs text-green-700 dark:text-green-300">Active</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-400">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.users.show', $user) }}" class="text-sm text-green-600 hover:text-green-700">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>{{ $users->links() }}</div>
    </div>
</x-layouts::app>
