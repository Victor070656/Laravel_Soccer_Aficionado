<x-layouts::app :title="__('Post')">
    <div class="max-w-3xl mx-auto space-y-6">
        {{-- Post --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center text-green-700 dark:text-green-300 font-bold">
                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('profiles.show', $post->user) }}" class="font-semibold text-zinc-900 dark:text-white hover:text-green-600">{{ $post->user->name }}</a>
                            <span class="text-xs text-zinc-400">{{ $post->created_at->diffForHumans() }}</span>
                        </div>
                        @can('delete', $post)
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete this post?')">
                            @csrf @method('DELETE')
                            <button class="text-xs text-red-400 hover:text-red-600">Delete</button>
                        </form>
                        @endcan
                    </div>

                    <p class="mt-3 text-zinc-700 dark:text-zinc-300 whitespace-pre-line">{{ $post->body }}</p>

                    @if($post->media)
                    <div class="mt-4 grid gap-2 {{ count($post->media) > 1 ? 'grid-cols-2' : '' }}">
                        @foreach($post->media as $media)
                        <img src="{{ asset('storage/' . $media) }}" alt="" class="rounded-lg w-full object-cover">
                        @endforeach
                    </div>
                    @endif

                    <div class="flex items-center gap-6 mt-4 pt-4 border-t border-zinc-100 dark:border-zinc-700">
                        <form action="{{ route('posts.like', $post) }}" method="POST" class="inline">
                            @csrf
                            <button class="flex items-center gap-1 text-sm {{ auth()->user()->hasLiked($post) ? 'text-green-600 font-bold' : 'text-zinc-400 hover:text-green-600' }}">
                                ♥ {{ $post->likes_count }} Likes
                            </button>
                        </form>
                        <span class="text-sm text-zinc-400">💬 {{ $post->comments_count }} Comments</span>
                        <span class="text-sm text-zinc-400">🔗 {{ $post->shares_count }} Shares</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Share --}}
        <form action="{{ route('posts.share', $post) }}" method="POST" class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
            @csrf
            <div class="flex items-center gap-3">
                <input type="text" name="comment" placeholder="Add a comment and share..." class="flex-1 rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white text-sm">
                <button type="submit" class="rounded-lg bg-zinc-100 dark:bg-zinc-700 px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 transition">Share</button>
            </div>
        </form>

        {{-- Comment Form --}}
        <form action="{{ route('posts.comment', $post) }}" method="POST" class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
            @csrf
            <textarea name="body" rows="2" placeholder="Write a comment..." class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white text-sm" required></textarea>
            <div class="flex justify-end mt-2">
                <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-700">Comment</button>
            </div>
        </form>

        {{-- Comments --}}
        <div class="space-y-4">
            <h3 class="font-bold text-zinc-900 dark:text-white">Comments ({{ $post->comments_count }})</h3>
            @forelse($post->comments as $comment)
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center text-xs font-bold text-zinc-600 dark:text-zinc-300">
                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('profiles.show', $comment->user) }}" class="font-semibold text-sm text-zinc-900 dark:text-white hover:text-green-600">{{ $comment->user->name }}</a>
                            <span class="text-xs text-zinc-400">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="mt-1 text-sm text-zinc-700 dark:text-zinc-300">{{ $comment->body }}</p>

                        {{-- Replies --}}
                        @if($comment->replies->count())
                        <div class="mt-3 ml-4 space-y-3 border-l-2 border-zinc-100 dark:border-zinc-700 pl-4">
                            @foreach($comment->replies as $reply)
                            <div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('profiles.show', $reply->user) }}" class="font-semibold text-xs text-zinc-900 dark:text-white hover:text-green-600">{{ $reply->user->name }}</a>
                                    <span class="text-xs text-zinc-400">{{ $reply->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-zinc-700 dark:text-zinc-300">{{ $reply->body }}</p>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        {{-- Reply Form --}}
                        <form action="{{ route('posts.comment', $post) }}" method="POST" class="mt-2 flex gap-2">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                            <input type="text" name="body" placeholder="Reply..." class="flex-1 rounded border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white text-xs py-1">
                            <button type="submit" class="text-xs text-green-600 hover:text-green-700 font-medium">Reply</button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-sm text-zinc-400">No comments yet. Start the conversation!</p>
            @endforelse
        </div>

        {{-- Report --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
            <details>
                <summary class="text-xs text-zinc-400 cursor-pointer hover:text-red-500">Report this post</summary>
                <form action="{{ route('reports.store') }}" method="POST" class="mt-3 space-y-3">
                    @csrf
                    <input type="hidden" name="reportable_type" value="post">
                    <input type="hidden" name="reportable_id" value="{{ $post->id }}">
                    <select name="reason" required class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white text-sm">
                        <option value="">Select reason</option>
                        <option value="spam">Spam</option>
                        <option value="harassment">Harassment</option>
                        <option value="hate_speech">Hate Speech</option>
                        <option value="misinformation">Misinformation</option>
                        <option value="other">Other</option>
                    </select>
                    <textarea name="description" rows="2" placeholder="Additional details (optional)" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white text-sm"></textarea>
                    <button type="submit" class="rounded-lg bg-red-600 px-3 py-1.5 text-xs text-white hover:bg-red-700">Submit Report</button>
                </form>
            </details>
        </div>
    </div>
</x-layouts::app>
