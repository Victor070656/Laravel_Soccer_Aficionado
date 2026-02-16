<x-layouts::app :title="__('Moderation - Reports')">
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex items-center gap-4">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Reports</h1>
            <a href="{{ route('admin.moderation.pendingPosts') }}" class="text-sm text-green-600 hover:text-green-700">Pending Posts →</a>
        </div>

        <div class="space-y-4">
            @forelse($reports as $report)
            <div class="rounded-xl border {{ $report->status === 'pending' ? 'border-amber-200 dark:border-amber-800' : 'border-zinc-200 dark:border-zinc-700' }} bg-white dark:bg-zinc-800 p-6">
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
                            <button class="rounded-lg bg-green-600 px-3 py-1.5 text-xs text-white hover:bg-green-700">Resolve</button>
                        </form>
                        <form action="{{ route('admin.moderation.reviewReport', $report) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="dismissed">
                            <input type="hidden" name="action_taken" value="Report dismissed after review">
                            <button class="rounded-lg border border-zinc-300 dark:border-zinc-600 px-3 py-1.5 text-xs text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-700">Dismiss</button>
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
            <div class="text-center py-12 text-zinc-400">
                <p class="text-4xl mb-3">🛡️</p>
                <p class="text-lg">No reports</p>
            </div>
            @endforelse
        </div>

        <div>{{ $reports->links() }}</div>
    </div>
</x-layouts::app>
