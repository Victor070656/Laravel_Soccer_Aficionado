<x-layouts::app :title="__('Admin Dashboard')">
    <div class="max-w-7xl mx-auto space-y-8">
        {{-- Header --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-800 via-gray-800 to-zinc-800 p-8">
            <div class="absolute top-0 right-0 w-64 h-64 bg-green-500/10 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-1/3 w-32 h-32 bg-emerald-500/10 rounded-full translate-y-1/2"></div>
            <div class="relative">
                <h1 class="text-3xl font-bold text-white">Admin Dashboard</h1>
                <p class="text-gray-400 mt-1">Overview of your platform activity</p>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="rounded-2xl bg-gradient-to-br from-blue-500/10 to-blue-600/5 border border-blue-500/20 p-5 hover-lift transition-all duration-300">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center text-lg">👥</div>
                    <div>
                        <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['users']) }}</p>
                        <p class="text-xs text-zinc-500 font-medium">Users</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-green-500/10 to-green-600/5 border border-green-500/20 p-5 hover-lift transition-all duration-300">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-green-500/20 flex items-center justify-center text-lg">📝</div>
                    <div>
                        <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['posts']) }}</p>
                        <p class="text-xs text-zinc-500 font-medium">Posts</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-purple-500/10 to-purple-600/5 border border-purple-500/20 p-5 hover-lift transition-all duration-300">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center text-lg">🌍</div>
                    <div>
                        <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['communities']) }}</p>
                        <p class="text-xs text-zinc-500 font-medium">Communities</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-red-500/10 to-red-600/5 border border-red-500/20 p-5 hover-lift transition-all duration-300">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-red-500/20 flex items-center justify-center text-lg">🚨</div>
                    <div>
                        <p class="text-2xl font-bold text-red-500">{{ number_format($stats['reports']) }}</p>
                        <p class="text-xs text-zinc-500 font-medium">Pending Reports</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-emerald-500/10 to-emerald-600/5 border border-emerald-500/20 p-5 hover-lift transition-all duration-300">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center text-lg">⚽</div>
                    <div>
                        <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['clubs']) }}</p>
                        <p class="text-xs text-zinc-500 font-medium">Clubs</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-orange-500/10 to-orange-600/5 border border-orange-500/20 p-5 hover-lift transition-all duration-300">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-orange-500/20 flex items-center justify-center text-lg">🏟️</div>
                    <div>
                        <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['matches']) }}</p>
                        <p class="text-xs text-zinc-500 font-medium">Matches</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-cyan-500/10 to-cyan-600/5 border border-cyan-500/20 p-5 hover-lift transition-all duration-300">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-cyan-500/20 flex items-center justify-center text-lg">📊</div>
                    <div>
                        <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['polls']) }}</p>
                        <p class="text-xs text-zinc-500 font-medium">Polls</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-yellow-500/10 to-yellow-600/5 border border-yellow-500/20 p-5 hover-lift transition-all duration-300">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-yellow-500/20 flex items-center justify-center text-lg">🏆</div>
                    <div>
                        <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['competitions']) }}</p>
                        <p class="text-xs text-zinc-500 font-medium">Competitions</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Recent Reports --}}
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700/50 bg-white dark:bg-zinc-800/50 backdrop-blur-sm overflow-hidden">
                <div class="flex items-center justify-between p-6 pb-4 border-b border-zinc-100 dark:border-zinc-700/50">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                        <h2 class="font-bold text-zinc-900 dark:text-white">Recent Reports</h2>
                    </div>
                    <a href="{{ route('admin.moderation.reports') }}" class="text-sm text-green-500 hover:text-green-400 font-medium">View all →</a>
                </div>
                <div class="p-6 pt-2">
                    @forelse($recentReports as $report)
                    <div class="flex items-center justify-between py-3 border-b border-zinc-100 dark:border-zinc-700/50 last:border-0">
                        <div>
                            <p class="text-sm text-zinc-700 dark:text-zinc-300 font-medium">{{ $report->reason }}</p>
                            <p class="text-xs text-zinc-400 mt-0.5">{{ class_basename($report->reportable_type) }} • by {{ $report->reporter->name ?? 'Deleted' }}</p>
                        </div>
                        <span class="text-xs text-zinc-400 whitespace-nowrap ml-3">{{ $report->created_at->diffForHumans() }}</span>
                    </div>
                    @empty
                    <p class="text-sm text-zinc-400 py-4 text-center">No pending reports 🎉</p>
                    @endforelse
                </div>
            </div>

            {{-- Recent Users --}}
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700/50 bg-white dark:bg-zinc-800/50 backdrop-blur-sm overflow-hidden">
                <div class="flex items-center justify-between p-6 pb-4 border-b border-zinc-100 dark:border-zinc-700/50">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        <h2 class="font-bold text-zinc-900 dark:text-white">Recent Users</h2>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-green-500 hover:text-green-400 font-medium">View all →</a>
                </div>
                <div class="p-6 pt-2">
                    @foreach($recentUsers as $user)
                    <div class="flex items-center justify-between py-3 border-b border-zinc-100 dark:border-zinc-700/50 last:border-0">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-xs font-bold text-white shadow-lg shadow-green-500/20">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                            <div>
                                <a href="{{ route('admin.users.show', $user) }}" class="text-sm font-medium text-zinc-900 dark:text-white hover:text-green-500 transition-colors">{{ $user->name }}</a>
                                <p class="text-xs text-zinc-400">{{ $user->email }}</p>
                            </div>
                        </div>
                        <span class="text-xs text-zinc-400 whitespace-nowrap ml-3">{{ $user->created_at->diffForHumans() }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.users.index') }}" class="group rounded-2xl border border-zinc-200 dark:border-zinc-700/50 bg-white dark:bg-zinc-800/50 p-6 text-center hover:border-blue-400 dark:hover:border-blue-500/50 transition-all duration-300 hover-lift">
                <div class="w-12 h-12 mx-auto rounded-2xl bg-blue-500/10 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">👥</div>
                <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mt-3">Manage Users</p>
            </a>
            <a href="{{ route('admin.clubs.index') }}" class="group rounded-2xl border border-zinc-200 dark:border-zinc-700/50 bg-white dark:bg-zinc-800/50 p-6 text-center hover:border-green-400 dark:hover:border-green-500/50 transition-all duration-300 hover-lift">
                <div class="w-12 h-12 mx-auto rounded-2xl bg-green-500/10 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">⚽</div>
                <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mt-3">Manage Clubs</p>
            </a>
            <a href="{{ route('admin.matches.index') }}" class="group rounded-2xl border border-zinc-200 dark:border-zinc-700/50 bg-white dark:bg-zinc-800/50 p-6 text-center hover:border-orange-400 dark:hover:border-orange-500/50 transition-all duration-300 hover-lift">
                <div class="w-12 h-12 mx-auto rounded-2xl bg-orange-500/10 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">🏟️</div>
                <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mt-3">Manage Matches</p>
            </a>
            <a href="{{ route('admin.moderation.reports') }}" class="group rounded-2xl border border-zinc-200 dark:border-zinc-700/50 bg-white dark:bg-zinc-800/50 p-6 text-center hover:border-red-400 dark:hover:border-red-500/50 transition-all duration-300 hover-lift">
                <div class="w-12 h-12 mx-auto rounded-2xl bg-red-500/10 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">🛡️</div>
                <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mt-3">Moderation</p>
            </a>
        </div>
    </div>
</x-layouts::app>
