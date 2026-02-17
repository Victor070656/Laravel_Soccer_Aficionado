<x-layouts::app :title="__('Pending Posts')">
    <div class="max-w-4xl mx-auto space-y-6">
        <a href="{{ route('admin.moderation.reports') }}" class="inline-flex items-center gap-1 text-sm text-green-500 hover:text-green-400 font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Reports
        </a>
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-amber-500 via-yellow-500 to-orange-500 p-8">
            <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <h1 class="text-3xl font-bold text-white relative">📋 Pending Posts</h1>
            <p class="text-amber-100 mt-1 relative">Review posts awaiting approval</p>
        </div>

        @forelse($posts as $post)
        <div class="rounded-2xl border border-amber-200 dark:border-amber-800/50 bg-white dark:bg-zinc-800/50 backdrop-blur-sm p-6 hover-lift transition-all duration-300">
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
                    <button class="rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:from-green-500 hover:to-emerald-500 shadow-lg shadow-green-500/25 transition-all">Approve</button>
                </form>
                <form action="{{ route('admin.moderation.rejectPost', $post) }}" method="POST" onsubmit="return confirm('Reject this post?')">
                    @csrf
                    <button class="rounded-xl bg-gradient-to-r from-red-600 to-rose-600 px-5 py-2.5 text-sm font-semibold text-white hover:from-red-500 hover:to-rose-500 shadow-lg shadow-red-500/25 transition-all">Reject</button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-16 text-zinc-400">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-green-500/10 flex items-center justify-center text-3xl mb-4">✅</div>
            <p class="text-lg font-semibold text-zinc-300">No pending posts</p>
            <p class="text-sm text-zinc-500 mt-1">All posts have been reviewed</p>
        </div>
        @endforelse

        <div>{{ $posts->links() }}</div>
    </div>
</x-layouts::app>
