<x-layouts::app :title="__('Admin Dashboard')">
    <div class="max-w-7xl mx-auto space-y-8">
        {{-- Header --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-primary via-primary/80 to-primary/60 p-8 text-on-primary shadow-xl">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-1/3 w-32 h-32 bg-white/10 rounded-full translate-y-1/2"></div>
            <div class="relative">
                <h1 class="text-3xl font-bold text-on-primary">Admin Dashboard</h1>
                <p class="text-on-primary/70 mt-1">Overview of your platform activity</p>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="rounded-2xl bg-gradient-to-br from-primary/20 to-primary/10 border border-primary/30 p-5 hover-lift transition-all duration-300 glass-edge">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary/20 flex items-center justify-center text-lg">👥</div>
                    <div>
                        <p class="text-2xl font-bold text-on-surface">{{ number_format($stats['users']) }}</p>
                        <p class="text-xs text-on-surface-variant font-medium">Users</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-primary/20 to-primary/10 border border-primary/30 p-5 hover-lift transition-all duration-300 glass-edge">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary/20 flex items-center justify-center text-lg">📝</div>
                    <div>
                        <p class="text-2xl font-bold text-on-surface">{{ number_format($stats['posts']) }}</p>
                        <p class="text-xs text-on-surface-variant font-medium">Posts</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-secondary/20 to-secondary/10 border border-secondary/30 p-5 hover-lift transition-all duration-300 glass-edge">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-secondary/20 flex items-center justify-center text-lg">🌍</div>
                    <div>
                        <p class="text-2xl font-bold text-on-surface">{{ number_format($stats['communities']) }}</p>
                        <p class="text-xs text-on-surface-variant font-medium">Communities</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-secondary/20 to-secondary/10 border border-secondary/30 p-5 hover-lift transition-all duration-300 glass-edge">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-secondary/20 flex items-center justify-center text-lg">🚨</div>
                    <div>
                        <p class="text-2xl font-bold text-secondary">{{ number_format($stats['pending_reports']) }}</p>
                        <p class="text-xs text-on-surface-variant font-medium">Pending Reports</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-secondary/20 to-secondary/10 border border-secondary/30 p-5 hover-lift transition-all duration-300 glass-edge">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-secondary/20 flex items-center justify-center text-lg">💬</div>
                    <div>
                        <p class="text-2xl font-bold text-on-surface">{{ number_format($stats['comments']) }}</p>
                        <p class="text-xs text-on-surface-variant font-medium">Comments</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-tertiary/20 to-tertiary/10 border border-tertiary/30 p-5 hover-lift transition-all duration-300 glass-edge">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-tertiary/20 flex items-center justify-center text-lg">❤️</div>
                    <div>
                        <p class="text-2xl font-bold text-on-surface">{{ number_format($stats['likes']) }}</p>
                        <p class="text-xs text-on-surface-variant font-medium">Likes</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-primary/20 to-primary/10 border border-primary/30 p-5 hover-lift transition-all duration-300 glass-edge">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary/20 flex items-center justify-center text-lg">📊</div>
                    <div>
                        <p class="text-2xl font-bold text-on-surface">{{ number_format($stats['polls']) }}</p>
                        <p class="text-xs text-on-surface-variant font-medium">Polls</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-tertiary/20 to-tertiary/10 border border-tertiary/30 p-5 hover-lift transition-all duration-300 glass-edge">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-tertiary/20 flex items-center justify-center text-lg">🚫</div>
                    <div>
                        <p class="text-2xl font-bold text-on-surface">{{ number_format($stats['banned_users']) }}</p>
                        <p class="text-xs text-on-surface-variant font-medium">Banned Users</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Recent Reports --}}
            <div class="rounded-2xl border border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container backdrop-blur-sm overflow-hidden glass-edge">
                <div class="flex items-center justify-between p-6 pb-4 border-b border-outline-variant/20 dark:border-outline-variant/30">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                        <h2 class="font-bold text-on-surface">Recent Reports</h2>
                    </div>
                    <a href="{{ route('admin.moderation.reports') }}" class="text-sm text-primary hover:text-primary/80 font-medium">View all →</a>
                </div>
                <div class="p-6 pt-2">
                    @forelse($recentReports as $report)
                    <div class="flex items-center justify-between py-3 border-b border-outline-variant/20 dark:border-outline-variant/30 last:border-0">
                        <div>
                            <p class="text-sm text-on-surface font-medium">{{ $report->reason }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">{{ class_basename($report->reportable_type) }} • by {{ $report->reporter->name ?? 'Deleted' }}</p>
                        </div>
                        <span class="text-xs text-on-surface-variant whitespace-nowrap ml-3">{{ $report->created_at->diffForHumans() }}</span>
                    </div>
                    @empty
                    <p class="text-sm text-on-surface-variant py-4 text-center">No pending reports 🎉</p>
                    @endforelse
                </div>
            </div>

            {{-- Recent Users --}}
            <div class="rounded-2xl border border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container backdrop-blur-sm overflow-hidden glass-edge">
                <div class="flex items-center justify-between p-6 pb-4 border-b border-outline-variant/20 dark:border-outline-variant/30">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-primary"></span>
                        <h2 class="font-bold text-on-surface">Recent Users</h2>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-primary hover:text-primary/80 font-medium">View all →</a>
                </div>
                <div class="p-6 pt-2">
                    @foreach($recentUsers as $user)
                    <div class="flex items-center justify-between py-3 border-b border-outline-variant/20 dark:border-outline-variant/30 last:border-0">
                        <div class="flex items-center gap-3">
                            @if($user->avatar)
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-9 h-9 rounded-full object-cover shadow-lg">
                            @else
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary to-primary/70 flex items-center justify-center text-xs font-bold text-on-primary shadow-lg shadow-primary/20">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                            @endif
                            <div>
                                <a href="{{ route('admin.users.show', $user) }}" class="text-sm font-medium text-on-surface hover:text-primary transition-colors">{{ $user->name }}</a>
                                <p class="text-xs text-on-surface-variant">{{ $user->email }}</p>
                            </div>
                        </div>
                        <span class="text-xs text-on-surface-variant whitespace-nowrap ml-3">{{ $user->created_at->diffForHumans() }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.users.index') }}" class="group rounded-2xl border border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container p-6 text-center hover:border-primary/50 dark:hover:border-primary/50 transition-all duration-300 hover-lift glass-edge">
                <div class="w-12 h-12 mx-auto rounded-2xl bg-primary/20 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">👥</div>
                <p class="text-sm font-semibold text-on-surface mt-3">Manage Users</p>
            </a>
            <a href="{{ route('admin.communities.index') }}" class="group rounded-2xl border border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container p-6 text-center hover:border-secondary/50 dark:hover:border-secondary/50 transition-all duration-300 hover-lift glass-edge">
                <div class="w-12 h-12 mx-auto rounded-2xl bg-secondary/20 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">🌍</div>
                <p class="text-sm font-semibold text-on-surface mt-3">Communities</p>
            </a>
            <a href="{{ route('admin.polls.index') }}" class="group rounded-2xl border border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container p-6 text-center hover:border-primary/50 dark:hover:border-primary/50 transition-all duration-300 hover-lift glass-edge">
                <div class="w-12 h-12 mx-auto rounded-2xl bg-primary/20 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">📊</div>
                <p class="text-sm font-semibold text-on-surface mt-3">Manage Polls</p>
            </a>
            <a href="{{ route('admin.moderation.reports') }}" class="group rounded-2xl border border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container p-6 text-center hover:border-secondary/50 dark:hover:border-secondary/50 transition-all duration-300 hover-lift glass-edge">
                <div class="w-12 h-12 mx-auto rounded-2xl bg-secondary/20 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">🛡️</div>
                <p class="text-sm font-semibold text-on-surface mt-3">Moderation</p>
            </a>
            <a href="{{ route('admin.moderation.pendingPosts') }}" class="group rounded-2xl border border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container p-6 text-center hover:border-tertiary/50 dark:hover:border-tertiary/50 transition-all duration-300 hover-lift glass-edge">
                <div class="w-12 h-12 mx-auto rounded-2xl bg-tertiary/20 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">📝</div>
                <p class="text-sm font-semibold text-on-surface mt-3">Pending ({{ $stats['pending_posts'] + $stats['pending_comments'] }})</p>
            </a>
        </div>
    </div>
</x-layouts::app>
