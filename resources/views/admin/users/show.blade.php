<x-layouts::app :title="$user->name . ' - Admin'">
    <div class="max-w-4xl mx-auto space-y-6">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1 text-sm text-green-500 hover:text-green-400 font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Users
        </a>

        {{-- User Info --}}
        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700/50 bg-white dark:bg-zinc-800/50 backdrop-blur-sm p-6 shadow-xl shadow-black/5">
            <div class="flex items-start gap-4">
                @if($user->avatar)
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-2xl object-cover shadow-xl">
                @else
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-2xl font-bold text-white shadow-xl shadow-blue-500/25">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                @endif
                <div class="flex-1">
                    <h1 class="text-xl font-bold text-zinc-900 dark:text-white">{{ $user->name }}</h1>
                    <p class="text-zinc-500">{{ $user->email }}</p>
                    @if($user->username) <p class="text-sm text-zinc-400">{{ '@' . $user->username }}</p> @endif
                    <div class="flex items-center gap-4 mt-3">
                        <span class="text-sm text-zinc-500">📅 Joined {{ $user->created_at->format('M d, Y') }}</span>
                        <span class="text-sm text-zinc-500">🏆 {{ $user->points ?? 0 }} points</span>
                        @if($user->country) <span class="text-sm text-zinc-500">📍 {{ $user->country }}</span> @endif
                    </div>
                </div>
                <div>
                    @if($user->is_banned)
                    <span class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900 px-3 py-1 text-sm font-medium text-red-700 dark:text-red-300">Banned</span>
                    @else
                    <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900 px-3 py-1 text-sm font-medium text-green-700 dark:text-green-300">Active</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Ban / Unban --}}
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700/50 bg-white dark:bg-zinc-800/50 backdrop-blur-sm p-6 shadow-xl shadow-black/5">
                <h2 class="font-bold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">⚙️ Account Actions</h2>
                @if($user->is_banned)
                <div class="mb-4 p-3 rounded-lg bg-red-50 dark:bg-red-900/20 text-sm text-red-700 dark:text-red-300">
                    <p>Banned on {{ $user->banned_at?->format('M d, Y') }}</p>
                    @if($user->ban_reason) <p class="mt-1">Reason: {{ $user->ban_reason }}</p> @endif
                </div>
                <form action="{{ route('admin.users.unban', $user) }}" method="POST">
                    @csrf
                    <button class="w-full rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:from-green-500 hover:to-emerald-500 shadow-lg shadow-green-500/25 transition-all">Unban User</button>
                </form>
                @else
                <form action="{{ route('admin.users.ban', $user) }}" method="POST" class="space-y-3">
                    @csrf
                    <textarea name="reason" placeholder="Ban reason..." rows="2" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white text-sm" required></textarea>
                    <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-red-600 to-rose-600 px-4 py-2.5 text-sm font-semibold text-white hover:from-red-500 hover:to-rose-500 shadow-lg shadow-red-500/25 transition-all" onclick="return confirm('Ban this user?')">Ban User</button>
                </form>
                @endif
            </div>

            {{-- Roles --}}
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700/50 bg-white dark:bg-zinc-800/50 backdrop-blur-sm p-6 shadow-xl shadow-black/5">
                <h2 class="font-bold text-zinc-900 dark:text-white mb-4 flex items-center gap-2">🏷️ Roles</h2>
                <div class="space-y-2 mb-4">
                    @foreach($user->roles as $role)
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900 px-3 py-1 text-sm text-blue-700 dark:text-blue-300">{{ $role->name }}</span>
                        <form action="{{ route('admin.users.removeRole', [$user, $role]) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="text-xs text-red-400 hover:text-red-600">Remove</button>
                        </form>
                    </div>
                    @endforeach
                    @if($user->roles->isEmpty())
                    <p class="text-sm text-zinc-400">No roles assigned</p>
                    @endif
                </div>
                <form action="{{ route('admin.users.assignRole', $user) }}" method="POST" class="flex gap-2">
                    @csrf
                    <select name="role_id" class="flex-1 rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white text-sm">
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700">Assign</button>
                </form>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="rounded-2xl bg-gradient-to-br from-green-500/10 to-green-600/5 border border-green-500/20 p-5 text-center hover-lift transition-all duration-300">
                <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $user->posts()->count() }}</p>
                <p class="text-xs text-zinc-500 font-medium mt-1">Posts</p>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-blue-500/10 to-blue-600/5 border border-blue-500/20 p-5 text-center hover-lift transition-all duration-300">
                <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $user->comments()->count() }}</p>
                <p class="text-xs text-zinc-500 font-medium mt-1">Comments</p>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-purple-500/10 to-purple-600/5 border border-purple-500/20 p-5 text-center hover-lift transition-all duration-300">
                <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $user->communities()->count() }}</p>
                <p class="text-xs text-zinc-500 font-medium mt-1">Communities</p>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-amber-500/10 to-amber-600/5 border border-amber-500/20 p-5 text-center hover-lift transition-all duration-300">
                <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $user->followers()->count() }}</p>
                <p class="text-xs text-zinc-500 font-medium mt-1">Followers</p>
            </div>
        </div>
    </div>
</x-layouts::app>
