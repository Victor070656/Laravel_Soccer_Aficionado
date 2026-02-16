<x-layouts::app :title="__('Admin Dashboard')">
    <div class="max-w-6xl mx-auto space-y-6">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Admin Dashboard</h1>

        {{-- Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['users']) }}</p>
                <p class="text-sm text-zinc-500">Users</p>
            </div>
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['posts']) }}</p>
                <p class="text-sm text-zinc-500">Posts</p>
            </div>
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['communities']) }}</p>
                <p class="text-sm text-zinc-500">Communities</p>
            </div>
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                <p class="text-2xl font-bold text-red-600">{{ number_format($stats['reports']) }}</p>
                <p class="text-sm text-zinc-500">Pending Reports</p>
            </div>
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['clubs']) }}</p>
                <p class="text-sm text-zinc-500">Clubs</p>
            </div>
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['matches']) }}</p>
                <p class="text-sm text-zinc-500">Matches</p>
            </div>
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['polls']) }}</p>
                <p class="text-sm text-zinc-500">Polls</p>
            </div>
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['competitions']) }}</p>
                <p class="text-sm text-zinc-500">Competitions</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Recent Reports --}}
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-zinc-900 dark:text-white">Recent Reports</h2>
                    <a href="{{ route('admin.moderation.reports') }}" class="text-sm text-green-600 hover:text-green-700">View all</a>
                </div>
                @forelse($recentReports as $report)
                <div class="flex items-center justify-between py-2 border-b border-zinc-100 dark:border-zinc-700 last:border-0">
                    <div>
                        <p class="text-sm text-zinc-700 dark:text-zinc-300">{{ $report->reason }}</p>
                        <p class="text-xs text-zinc-400">{{ class_basename($report->reportable_type) }} • by {{ $report->reporter->name ?? 'Deleted' }}</p>
                    </div>
                    <span class="text-xs text-zinc-400">{{ $report->created_at->diffForHumans() }}</span>
                </div>
                @empty
                <p class="text-sm text-zinc-400">No pending reports 🎉</p>
                @endforelse
            </div>

            {{-- Recent Users --}}
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-zinc-900 dark:text-white">Recent Users</h2>
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-green-600 hover:text-green-700">View all</a>
                </div>
                @foreach($recentUsers as $user)
                <div class="flex items-center justify-between py-2 border-b border-zinc-100 dark:border-zinc-700 last:border-0">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center text-xs font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        <div>
                            <a href="{{ route('admin.users.show', $user) }}" class="text-sm font-medium text-zinc-900 dark:text-white hover:text-green-600">{{ $user->name }}</a>
                            <p class="text-xs text-zinc-400">{{ $user->email }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-zinc-400">{{ $user->created_at->diffForHumans() }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4 text-center hover:border-green-400 transition">
                <span class="text-2xl">👥</span>
                <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300 mt-2">Manage Users</p>
            </a>
            <a href="{{ route('admin.clubs.index') }}" class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4 text-center hover:border-green-400 transition">
                <span class="text-2xl">⚽</span>
                <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300 mt-2">Manage Clubs</p>
            </a>
            <a href="{{ route('admin.matches.index') }}" class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4 text-center hover:border-green-400 transition">
                <span class="text-2xl">🏟️</span>
                <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300 mt-2">Manage Matches</p>
            </a>
            <a href="{{ route('admin.moderation.reports') }}" class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4 text-center hover:border-green-400 transition">
                <span class="text-2xl">🛡️</span>
                <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300 mt-2">Moderation</p>
            </a>
        </div>
    </div>
</x-layouts::app>
