<x-layouts::app :title="__('Manage Users')">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-primary via-primary/80 to-primary/60 p-8 text-on-primary shadow-xl">
            <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <h1 class="text-3xl font-bold text-white relative">👥 User Management</h1>
            <p class="text-on-primary/70 mt-1 relative">Monitor and manage platform users</p>
        </div>

        {{-- Filters --}}
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap gap-3 p-4 rounded-2xl bg-surface-container dark:bg-surface-container border border-outline-variant/20 dark:border-outline-variant/30 glass-edge">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="focus-ring rounded-lg border-outline-variant/20 dark:border-outline-variant/30 dark:bg-surface-container-high dark:text-on-surface text-sm">
            <select name="role" class="focus-ring rounded-lg border-outline-variant/20 dark:border-outline-variant/30 dark:bg-surface-container-high dark:text-on-surface text-sm">
                <option value="">All Roles</option>
                @foreach($roles ?? [] as $role)
                <option value="{{ $role->slug }}" {{ request('role') === $role->slug ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
            <select name="status" class="focus-ring rounded-lg border-outline-variant/20 dark:border-outline-variant/30 dark:bg-surface-container-high dark:text-on-surface text-sm">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Banned</option>
            </select>
            <button type="submit" class="focus-ring btn-primary rounded-xl px-5 py-2 text-sm font-semibold text-on-primary shadow-lg shadow-primary/25">Filter</button>
        </form>

        {{-- Users Table --}}
        <div class="rounded-2xl border border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container backdrop-blur-sm overflow-hidden shadow-xl shadow-black/5 glass-edge">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container-high/50 dark:bg-surface-container-high/50">
                        <th class="px-4 py-3 text-left text-xs font-medium text-on-surface-variant uppercase">User</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-on-surface-variant uppercase">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-on-surface-variant uppercase">Roles</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-on-surface-variant uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-on-surface-variant uppercase">Joined</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-on-surface-variant uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20 dark:divide-outline-variant/30">
                    @foreach($users as $user)
                    <tr class="hover:bg-surface-container-high/50 dark:hover:bg-surface-container-high/50">
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.users.show', $user) }}" class="flex items-center gap-2 hover:text-primary">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary to-primary/70 flex items-center justify-center text-xs font-bold text-on-primary shadow-md shadow-primary/20">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                <span class="font-medium text-on-surface dark:text-on-surface">{{ $user->name }}</span>
                            </a>
                        </td>
                        <td class="px-4 py-3 text-sm text-on-surface-variant">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            @foreach($user->roles as $role)
                            <span class="inline-flex items-center rounded-full bg-primary/20 dark:bg-primary/25 px-2 py-0.5 text-xs text-primary">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td class="px-4 py-3">
                            @if($user->is_banned)
                            <span class="inline-flex items-center rounded-full bg-secondary/20 dark:bg-secondary/25 px-2 py-0.5 text-xs text-secondary">Banned</span>
                            @else
                            <span class="inline-flex items-center rounded-full bg-primary/20 dark:bg-primary/25 px-2 py-0.5 text-xs text-primary">Active</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-on-surface-variant">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.users.show', $user) }}" class="text-sm text-primary hover:text-primary/80">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>{{ $users->links() }}</div>
    </div>
</x-layouts::app>
