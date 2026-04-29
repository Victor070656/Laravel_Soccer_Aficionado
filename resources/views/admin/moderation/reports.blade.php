<x-layouts::app :title="__('Moderation - Reports')">
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-secondary via-secondary/80 to-secondary/60 p-8 text-on-secondary shadow-xl">
            <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="flex items-center justify-between relative">
                <div>
                    <h1 class="text-3xl font-bold text-white">🛡️ Reports</h1>
                    <p class="text-on-secondary/70 mt-1">Review and manage reported content</p>
                </div>
                <a href="{{ route('admin.moderation.pendingPosts') }}" class="rounded-xl bg-white/20 backdrop-blur-sm border border-white/30 px-5 py-2.5 text-sm font-semibold text-white hover:bg-white/30 transition-all duration-300">Pending Posts →</a>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($reports as $report)
            <div class="rounded-2xl border {{ $report->status === 'pending' ? 'border-tertiary/40 dark:border-tertiary/50' : 'border-outline-variant/20 dark:border-outline-variant/30' }} bg-surface-container dark:bg-surface-container backdrop-blur-sm p-6 hover-lift transition-all duration-300 glass-edge">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                @if($report->status === 'pending') bg-tertiary/20 dark:bg-tertiary/25 text-tertiary dark:text-tertiary
                                @elseif($report->status === 'resolved') bg-primary/20 dark:bg-primary/25 text-primary dark:text-primary
                                @else bg-surface-container-high dark:bg-surface-container-high text-on-surface-variant dark:text-on-surface-variant
                                @endif">{{ ucfirst($report->status) }}</span>
                            <span class="text-sm font-medium text-on-surface-variant">{{ class_basename($report->reportable_type) }} #{{ $report->reportable_id }}</span>
                        </div>
                        <p class="font-medium text-on-surface dark:text-on-surface">{{ ucfirst($report->reason) }}</p>
                        @if($report->description)
                        <p class="text-sm text-on-surface-variant dark:text-on-surface-variant mt-1">{{ $report->description }}</p>
                        @endif
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-xs text-on-surface-variant">Reported by {{ $report->reporter->name ?? 'Deleted' }}</span>
                            <span class="text-xs text-on-surface-variant">{{ $report->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @if($report->status === 'pending')
                    <div class="flex gap-2">
                        <form action="{{ route('admin.moderation.reviewReport', $report) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="resolved">
                            <input type="hidden" name="action_taken" value="Content reviewed and action taken">
                            <button class="rounded-xl bg-gradient-to-r from-primary to-primary/80 px-4 py-2 text-xs font-semibold text-on-primary hover:from-primary/90 hover:to-primary/70 shadow-lg shadow-primary/25 transition-all">Resolve</button>
                        </form>
                        <form action="{{ route('admin.moderation.reviewReport', $report) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="dismissed">
                            <input type="hidden" name="action_taken" value="Report dismissed after review">
                            <button class="rounded-xl border border-outline-variant/20 dark:border-outline-variant/30 px-4 py-2 text-xs font-semibold text-on-surface-variant dark:text-on-surface-variant hover:bg-surface-container-high dark:hover:bg-surface-container-high transition-all">Dismiss</button>
                        </form>
                    </div>
                    @endif
                </div>

                @if($report->reviewer)
                <div class="mt-3 pt-3 border-t border-outline-variant/20 dark:border-outline-variant/30">
                    <p class="text-xs text-on-surface-variant">Reviewed by {{ $report->reviewer->name }} • {{ $report->action_taken }}</p>
                </div>
                @endif
            </div>
            @empty
            <div class="text-center py-16 text-on-surface-variant">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-primary/20 flex items-center justify-center text-3xl mb-4">🛡️</div>
                <p class="text-lg font-semibold text-on-surface">No reports</p>
                <p class="text-sm text-on-surface-variant mt-1">All clear! No pending reports to review.</p>
            </div>
            @endforelse
        </div>

        <div>{{ $reports->links() }}</div>
    </div>
</x-layouts::app>
