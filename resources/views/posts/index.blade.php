<x-layouts::app :title="__('Feed')">
    <div class="space-y-6 max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Feed</h1>

        {{-- Create Post --}}
        @auth
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
            @csrf
            <textarea name="body" rows="3" placeholder="Share your thoughts..." class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white focus:border-green-500 focus:ring-green-500 text-sm" required></textarea>
            @error('body') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            <div class="flex items-center justify-between mt-2">
                <input type="file" name="media[]" multiple accept="image/*,video/*" class="text-xs text-zinc-500">
                <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 transition">Post</button>
            </div>
        </form>
        @endauth

        @forelse($posts as $post)
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center text-green-700 dark:text-green-300 font-bold text-sm">
                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="{{ route('profiles.show', $post->user) }}" class="font-semibold text-sm text-zinc-900 dark:text-white hover:text-green-600">{{ $post->user->name }}</a>
                        @if($post->community)
                        <span class="text-xs text-zinc-400">in</span>
                        <a href="{{ route('communities.show', $post->community) }}" class="text-xs text-green-600">{{ $post->community->name }}</a>
                        @endif
                        <span class="text-xs text-zinc-400">{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="mt-2 text-sm text-zinc-700 dark:text-zinc-300 whitespace-pre-line">{{ $post->body }}</p>

                    @if($post->media)
                    <div class="mt-3 grid gap-2 {{ count($post->media) > 1 ? 'grid-cols-2' : '' }}">
                        @foreach($post->media as $media)
                        <img src="{{ asset('storage/' . $media) }}" alt="" class="rounded-lg max-h-64 w-full object-cover">
                        @endforeach
                    </div>
                    @endif

                    <div class="flex items-center gap-4 mt-3">
                        @auth
                        <form action="{{ route('posts.like', $post) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center gap-1 text-xs {{ auth()->user()->hasLiked($post) ? 'text-green-600 font-bold' : 'text-zinc-400 hover:text-green-600' }} transition">
                                ♥ {{ $post->likes_count }}
                            </button>
                        </form>
                        @endauth
                        <a href="{{ route('posts.show', $post) }}" class="flex items-center gap-1 text-xs text-zinc-400 hover:text-green-600 transition">💬 {{ $post->comments_count }}</a>
                        <span class="text-xs text-zinc-400">🔗 {{ $post->shares_count }}</span>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12 text-zinc-400">No posts yet. Be the first to share!</div>
        @endforelse

        {{ $posts->links() }}
    </div>
</x-layouts::app>
