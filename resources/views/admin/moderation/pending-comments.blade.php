<x-layouts::app :title="__('Pending Comments')">
    <div class="max-w-7xl mx-auto space-y-6 p-2 sm:p-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Pending Comments</h1>
                <p class="text-sm text-zinc-500 mt-1">Review and approve or remove user comments.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.moderation.pendingPosts') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">Pending Posts →</a>
                <a href="{{ route('admin.moderation.reports') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">Reports →</a>
            </div>
        </div>

        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-100 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900/30">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-zinc-500 uppercase">User</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-zinc-500 uppercase">Comment</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-zinc-500 uppercase">On Post</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-zinc-500 uppercase">Date</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-zinc-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700/50">
                    @forelse($comments as $comment)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/30 transition">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                @if($comment->user?->avatar)
                                <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}" class="w-8 h-8 rounded-full object-cover">
                                @else
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-xs font-bold text-white">{{ strtoupper(substr($comment->user->name ?? '?', 0, 1)) }}</div>
                                @endif
                                <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $comment->user?->name ?? 'Deleted' }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-300 max-w-xs truncate">{{ Str::limit($comment->body, 100) }}</td>
                        <td class="px-5 py-4">
                            @if($comment->post)
                            <a href="{{ route('posts.show', $comment->post) }}" class="text-sm text-green-600 hover:text-green-700">{{ Str::limit($comment->post->body, 40) }}</a>
                            @else
                            <span class="text-sm text-zinc-400">Deleted post</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-sm text-zinc-500">{{ $comment->created_at->diffForHumans() }}</td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <form method="POST" action="{{ route('admin.moderation.approveComment', $comment) }}">
                                    @csrf
                                    <button type="submit" class="text-sm text-green-500 hover:text-green-700 font-medium">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('admin.moderation.deleteComment', $comment) }}" onsubmit="return confirm('Delete this comment?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-zinc-400">No pending comments 🎉</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $comments->links() }}
    </div>
</x-layouts::app>
