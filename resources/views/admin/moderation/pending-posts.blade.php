<x-layouts::app :title="__('Pending Posts')">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.moderation.reports') }}" class="text-sm text-green-600 hover:text-green-700">← Reports</a>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Pending Posts</h1>
        </div>

        @forelse($posts as $post)
        <div class="rounded-xl border border-amber-200 dark:border-amber-800 bg-white dark:bg-zinc-800 p-6">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center text-sm font-bold">
                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $post->user->name }}</span>
                        <span class="text-xs text-zinc-400">{{ $post->created_at->diffForHumans() }}</span>
                        @if($post->community)
                        <span class="text-xs text-green-600">in {{ $post->community->name }}</span>
                        @endif
                    </div>
                    <p class="text-zinc-700 dark:text-zinc-300">{{ $post->body }}</p>
                    @if($post->media)
                    <div class="mt-3 flex gap-2">
                        @foreach($post->media as $media)
                        <img src="{{ asset('storage/' . $media) }}" alt="" class="rounded-lg h-32 object-cover">
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-zinc-100 dark:border-zinc-700">
                <form action="{{ route('admin.moderation.approvePost', $post) }}" method="POST">
                    @csrf
                    <button class="rounded-lg bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-700">Approve</button>
                </form>
                <form action="{{ route('admin.moderation.rejectPost', $post) }}" method="POST" onsubmit="return confirm('Reject this post?')">
                    @csrf
                    <button class="rounded-lg bg-red-600 px-4 py-2 text-sm text-white hover:bg-red-700">Reject</button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-12 text-zinc-400">
            <p class="text-4xl mb-3">✅</p>
            <p class="text-lg">No pending posts</p>
            <p class="text-sm mt-1">All posts have been reviewed</p>
        </div>
        @endforelse

        <div>{{ $posts->links() }}</div>
    </div>
</x-layouts::app>
