<x-layouts::app :title="__('Moderation - Reports')">
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-red-500 via-rose-500 to-pink-500 p-8">
            <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="flex items-center justify-between relative">
                <div>
                    <h1 class="text-3xl font-bold text-white">🛡️ Reports</h1>
                    <p class="text-red-100 mt-1">Review and manage reported content</p>
                </div>
                <a href="{{ route('admin.moderation.pendingPosts') }}" class="rounded-xl bg-white/20 backdrop-blur-sm border border-white/30 px-5 py-2.5 text-sm font-semibold text-white hover:bg-white/30 transition-all duration-300">Pending Posts →</a>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($reports as $report)
            <div class="rounded-2xl border {{ $report->status === 'pending' ? 'border-amber-200 dark:border-amber-800/50' : 'border-zinc-200 dark:border-zinc-700/50' }} bg-white dark:bg-zinc-800/50 backdrop-blur-sm p-6 hover-lift transition-all duration-300">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                @if($report->status === 'pending') bg-amber-100 dark:bg-amber-900 text-amber-700 dark:text-amber-300
                                @elseif($report->status === 'resolved') bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300
                                @else bg-zinc-100 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-300
                                @endif">{{ ucfirst($report->status) }}</span>
                            <span class="text-sm font-medium text-zinc-500">{{ class_basename($report->reportable_type) }} #{{ $report->reportable_id }}</span>
                        </div>
                        <p class="font-medium text-zinc-900 dark:text-white">{{ ucfirst($report->reason) }}</p>
                        @if($report->description)
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">{{ $report->description }}</p>
                        @endif
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-xs text-zinc-400">Reported by {{ $report->reporter->name ?? 'Deleted' }}</span>
                            <span class="text-xs text-zinc-400">{{ $report->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @if($report->status === 'pending')
                    <div class="flex gap-2">
                        <form action="{{ route('admin.moderation.reviewReport', $report) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="resolved">
                            <input type="hidden" name="action_taken" value="Content reviewed and action taken">
                            <button class="rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:from-green-500 hover:to-emerald-500 shadow-lg shadow-green-500/25 transition-all">Resolve</button>
                        </form>
                        <form action="{{ route('admin.moderation.reviewReport', $report) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="dismissed">
                            <input type="hidden" name="action_taken" value="Report dismissed after review">
                            <button class="rounded-xl border border-zinc-300 dark:border-zinc-600 px-4 py-2 text-xs font-semibold text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-all">Dismiss</button>
                        </form>
                    </div>
                    @endif
                </div>

                @if($report->reviewer)
                <div class="mt-3 pt-3 border-t border-zinc-100 dark:border-zinc-700">
                    <p class="text-xs text-zinc-400">Reviewed by {{ $report->reviewer->name }} • {{ $report->action_taken }}</p>
                </div>
                @endif
            </div>
            @empty
            <div class="text-center py-16 text-zinc-400">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-green-500/10 flex items-center justify-center text-3xl mb-4">🛡️</div>
                <p class="text-lg font-semibold text-zinc-300">No reports</p>
                <p class="text-sm text-zinc-500 mt-1">All clear! No pending reports to review.</p>
            </div>
            @endforelse
        </div>

        <div>{{ $reports->links() }}</div>
    </div>
</x-layouts::app>
