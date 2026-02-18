<x-layouts::app :title="__('Platform Analytics')">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-violet-600 via-purple-600 to-fuchsia-600 p-8">
            <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="relative">
                <h1 class="text-3xl font-bold text-white">📊 Platform Analytics</h1>
                <p class="text-violet-100 mt-1">User growth, engagement metrics, and content moderation insights</p>
            </div>
        </div>

        {{-- Key Metrics --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach([
                ['label' => 'Total Users', 'value' => $engagement['total_users'], 'icon' => '👥', 'color' => 'blue'],
                ['label' => 'Active (7d)', 'value' => $engagement['active_users_7d'], 'icon' => '🟢', 'color' => 'green'],
                ['label' => 'Total Posts', 'value' => $engagement['total_posts'], 'icon' => '📝', 'color' => 'indigo'],
                ['label' => 'Pending Reports', 'value' => $engagement['pending_reports'], 'icon' => '⚠️', 'color' => 'red'],
            ] as $metric)
            <div class="rounded-2xl bg-gradient-to-br from-{{ $metric['color'] }}-500/10 to-{{ $metric['color'] }}-600/5 border border-{{ $metric['color'] }}-500/20 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-{{ $metric['color'] }}-500/20 flex items-center justify-center text-lg">{{ $metric['icon'] }}</div>
                    <div>
                        <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ number_format($metric['value']) }}</p>
                        <p class="text-xs text-zinc-500">{{ $metric['label'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Engagement Overview --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10">
                    <h3 class="font-bold text-zinc-900 dark:text-white text-sm">📈 Engagement Overview</h3>
                </div>
                <div class="p-5 space-y-3">
                    @foreach([
                        ['label' => 'Active Users (30d)', 'value' => $engagement['active_users_30d'], 'pct' => $engagement['total_users'] > 0 ? round(($engagement['active_users_30d'] / $engagement['total_users']) * 100) : 0],
                        ['label' => 'Posts Today', 'value' => $engagement['posts_today'], 'pct' => null],
                        ['label' => 'Posts This Week', 'value' => $engagement['posts_this_week'], 'pct' => null],
                        ['label' => 'Comments Today', 'value' => $engagement['comments_today'], 'pct' => null],
                        ['label' => 'Likes Today', 'value' => $engagement['likes_today'], 'pct' => null],
                        ['label' => 'Total Votes', 'value' => $engagement['total_votes'], 'pct' => null],
                        ['label' => 'Communities', 'value' => $engagement['total_communities'], 'pct' => null],
                        ['label' => 'Active Polls', 'value' => $engagement['active_polls'], 'pct' => null],
                        ['label' => 'Banned Users', 'value' => $engagement['banned_users'], 'pct' => null],
                    ] as $item)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-zinc-500">{{ $item['label'] }}</span>
                        <span class="font-medium text-zinc-900 dark:text-white">
                            {{ number_format($item['value']) }}
                            @if($item['pct'] !== null)
                            <span class="text-xs text-zinc-400">({{ $item['pct'] }}%)</span>
                            @endif
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Content Moderation --}}
            <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/10 dark:to-orange-900/10">
                    <h3 class="font-bold text-zinc-900 dark:text-white text-sm">🛡️ Content Moderation</h3>
                </div>
                <div class="p-5 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="rounded-xl bg-amber-50 dark:bg-amber-900/10 p-4 text-center">
                            <p class="text-2xl font-bold text-amber-700 dark:text-amber-300">{{ $moderationStats['pending_posts'] }}</p>
                            <p class="text-xs text-amber-600 dark:text-amber-400">Pending Posts</p>
                        </div>
                        <div class="rounded-xl bg-amber-50 dark:bg-amber-900/10 p-4 text-center">
                            <p class="text-2xl font-bold text-amber-700 dark:text-amber-300">{{ $moderationStats['pending_comments'] }}</p>
                            <p class="text-xs text-amber-600 dark:text-amber-400">Pending Comments</p>
                        </div>
                        <div class="rounded-xl bg-green-50 dark:bg-green-900/10 p-4 text-center">
                            <p class="text-2xl font-bold text-green-700 dark:text-green-300">{{ $moderationStats['approved_posts'] }}</p>
                            <p class="text-xs text-green-600 dark:text-green-400">Approved Posts</p>
                        </div>
                        <div class="rounded-xl bg-green-50 dark:bg-green-900/10 p-4 text-center">
                            <p class="text-2xl font-bold text-green-700 dark:text-green-300">{{ $moderationStats['approved_comments'] }}</p>
                            <p class="text-xs text-green-600 dark:text-green-400">Approved Comments</p>
                        </div>
                    </div>

                    @if(!empty($reportsByReason))
                    <div class="pt-3 border-t border-zinc-100 dark:border-zinc-700/50">
                        <h4 class="text-xs font-semibold text-zinc-500 uppercase mb-3">Reports by Reason</h4>
                        @foreach($reportsByReason as $reason => $count)
                        <div class="flex items-center justify-between py-1.5">
                            <span class="text-sm text-zinc-600 dark:text-zinc-400 capitalize">{{ $reason }}</span>
                            <div class="flex items-center gap-2">
                                <div class="w-24 h-2 rounded-full bg-zinc-100 dark:bg-zinc-700 overflow-hidden">
                                    <div class="h-full rounded-full bg-red-400" style="width: {{ min(100, ($count / max(array_values($reportsByReason))) * 100) }}%"></div>
                                </div>
                                <span class="text-xs font-medium text-zinc-500 w-8 text-right">{{ $count }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- User Growth (30d) --}}
            <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/10 dark:to-emerald-900/10">
                    <h3 class="font-bold text-zinc-900 dark:text-white text-sm">👥 User Registrations (30d)</h3>
                </div>
                <div class="p-5">
                    @if(!empty($userGrowth))
                    @php $maxVal = max(array_values($userGrowth) ?: [1]); @endphp
                    <div class="flex items-end gap-0.5 h-32">
                        @foreach($userGrowth as $date => $count)
                        <div class="flex-1 flex flex-col items-center justify-end group relative">
                            <div class="absolute -top-6 bg-zinc-900 text-white text-[10px] px-1.5 py-0.5 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($date)->format('M d') }}: {{ $count }}
                            </div>
                            <div class="w-full rounded-t bg-gradient-to-t from-green-500 to-emerald-400 transition-all hover:from-green-400 hover:to-emerald-300" style="height: {{ max(4, ($count / $maxVal) * 100) }}%"></div>
                        </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-zinc-400 mt-2 text-center">{{ array_sum($userGrowth) }} new users in the last 30 days</p>
                    @else
                    <p class="text-sm text-zinc-400 text-center py-8">No registration data available.</p>
                    @endif
                </div>
            </div>

            {{-- Post Activity (30d) --}}
            <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-indigo-50 to-violet-50 dark:from-indigo-900/10 dark:to-violet-900/10">
                    <h3 class="font-bold text-zinc-900 dark:text-white text-sm">📝 Post Activity (30d)</h3>
                </div>
                <div class="p-5">
                    @if(!empty($postActivity))
                    @php $maxVal = max(array_values($postActivity) ?: [1]); @endphp
                    <div class="flex items-end gap-0.5 h-32">
                        @foreach($postActivity as $date => $count)
                        <div class="flex-1 flex flex-col items-center justify-end group relative">
                            <div class="absolute -top-6 bg-zinc-900 text-white text-[10px] px-1.5 py-0.5 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($date)->format('M d') }}: {{ $count }}
                            </div>
                            <div class="w-full rounded-t bg-gradient-to-t from-indigo-500 to-violet-400 transition-all hover:from-indigo-400 hover:to-violet-300" style="height: {{ max(4, ($count / $maxVal) * 100) }}%"></div>
                        </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-zinc-400 mt-2 text-center">{{ array_sum($postActivity) }} posts in the last 30 days</p>
                    @else
                    <p class="text-sm text-zinc-400 text-center py-8">No post activity data available.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Top Communities --}}
            <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-purple-50 to-fuchsia-50 dark:from-purple-900/10 dark:to-fuchsia-900/10">
                    <h3 class="font-bold text-zinc-900 dark:text-white text-sm">🏆 Top Communities</h3>
                </div>
                <div class="p-5 space-y-2">
                    @forelse($topCommunities as $i => $community)
                    <a href="{{ route('communities.show', $community->slug) }}" class="flex items-center justify-between p-3 rounded-xl bg-zinc-50 dark:bg-zinc-700/30 hover:bg-zinc-100 dark:hover:bg-zinc-700/50 transition">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full {{ $i < 3 ? 'bg-amber-100 text-amber-700' : 'bg-zinc-100 text-zinc-500' }} flex items-center justify-center text-xs font-bold">{{ $i + 1 }}</span>
                            <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $community->name }}</span>
                        </div>
                        <span class="text-xs text-zinc-400">{{ number_format($community->members_count) }} members</span>
                    </a>
                    @empty
                    <p class="text-sm text-zinc-400 text-center py-4">No communities yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- Top Users --}}
            <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-emerald-50 to-green-50 dark:from-emerald-900/10 dark:to-green-900/10">
                    <h3 class="font-bold text-zinc-900 dark:text-white text-sm">⭐ Most Active Users</h3>
                </div>
                <div class="p-5 space-y-2">
                    @forelse($topUsers as $i => $user)
                    <a href="{{ route('profiles.show', $user->username) }}" class="flex items-center justify-between p-3 rounded-xl bg-zinc-50 dark:bg-zinc-700/30 hover:bg-zinc-100 dark:hover:bg-zinc-700/50 transition">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full {{ $i < 3 ? 'bg-amber-100 text-amber-700' : 'bg-zinc-100 text-zinc-500' }} flex items-center justify-center text-xs font-bold">{{ $i + 1 }}</span>
                            <div>
                                <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $user->name }}</span>
                                <span class="text-xs text-zinc-400 ml-1">{{ '@' . $user->username }}</span>
                            </div>
                        </div>
                        <span class="text-xs font-semibold text-green-600">{{ number_format($user->points) }} pts</span>
                    </a>
                    @empty
                    <p class="text-sm text-zinc-400 text-center py-4">No users yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
