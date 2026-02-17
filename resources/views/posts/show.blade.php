<x-layouts::app :title="__('Post')">
    <div class="max-w-3xl mx-auto space-y-6 p-2 sm:p-4">
        {{-- Post Card --}}
        <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-bold text-lg shadow-md">
                        {{ strtoupper(substr($post->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 flex-wrap">
                                <a href="{{ route('profiles.show', $post->user) }}" class="font-bold text-zinc-900 dark:text-white hover:text-green-600 transition">{{ $post->user->name }}</a>
                                <span class="text-xs text-zinc-400 dark:text-zinc-500">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                            @can('delete', $post)
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete this post?')">
                                @csrf @method('DELETE')
                                <button class="text-xs text-zinc-400 hover:text-red-600 transition font-medium">Delete</button>
                            </form>
                            @endcan
                        </div>

                        <p class="mt-3 text-zinc-700 dark:text-zinc-300 whitespace-pre-line leading-relaxed">{{ $post->body }}</p>

                        @if($post->media)
                        <div class="mt-4 grid gap-2 {{ count($post->media) > 1 ? 'grid-cols-2' : '' }}">
                            @foreach($post->media as $media)
                            <img loading="lazy" decoding="async" src="{{ asset('storage/' . $media) }}" alt="" class="rounded-xl w-full object-cover">
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Interactions Bar --}}
            <div class="flex items-center gap-6 px-6 py-4 border-t border-zinc-100 dark:border-zinc-700/60 bg-zinc-50/50 dark:bg-zinc-900/30">
                <form action="{{ route('posts.like', $post) }}" method="POST" class="inline">
                    @csrf
                    <button class="flex items-center gap-2 text-sm font-medium {{ auth()->user()->hasLiked($post) ? 'text-green-600 font-bold' : 'text-zinc-400 hover:text-green-600' }} transition">
                        <span class="text-lg">♥</span>
                        <span>{{ $post->likes_count }} Likes</span>
                    </button>
                </form>
                <span class="flex items-center gap-2 text-sm text-zinc-400">
                    <span class="text-lg">💬</span>
                    <span>{{ $post->comments_count }} Comments</span>
                </span>
                <span class="flex items-center gap-2 text-sm text-zinc-400">
                    <span class="text-lg">🔗</span>
                    <span>{{ $post->shares_count }} Shares</span>
                </span>
            </div>
        </div>

        {{-- Share --}}
        <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
            <form action="{{ route('posts.share', $post) }}" method="POST" class="p-5">
                @csrf
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 text-sm">🔗</span>
                    <input type="text" name="comment" placeholder="Add a comment and share..." class="flex-1 rounded-xl border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white focus:border-green-500 focus:ring-green-500/20 text-sm">
                    <button type="submit" class="rounded-xl border border-zinc-200 dark:border-zinc-600 px-5 py-2.5 text-sm font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition">Share</button>
                </div>
            </form>
        </div>

        {{-- Comment Form --}}
        <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/10 dark:to-emerald-900/10">
                <h3 class="font-semibold text-zinc-900 dark:text-white flex items-center gap-2 text-sm">
                    <span class="w-7 h-7 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 text-xs">💬</span>
                    Add a Comment
                </h3>
            </div>
            <form action="{{ route('posts.comment', $post) }}" method="POST" class="p-5">
                @csrf
                <textarea name="body" rows="2" placeholder="Write a comment..." class="w-full rounded-xl border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white focus:border-green-500 focus:ring-green-500/20 text-sm resize-none" required></textarea>
                <div class="flex justify-end mt-3">
                    <button type="submit" class="rounded-xl bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-2.5 text-sm font-semibold text-white hover:from-green-500 hover:to-emerald-400 transition-all shadow-md shadow-green-600/20 hover:scale-105">Comment</button>
                </div>
            </form>
        </div>

        {{-- Comments --}}
        <div class="space-y-4">
            <h3 class="font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center text-violet-600 text-sm">💬</span>
                Comments ({{ $post->comments_count }})
            </h3>

            @forelse($post->comments as $comment)
            <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
                <div class="p-5">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-violet-400 to-purple-500 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('profiles.show', $comment->user) }}" class="font-semibold text-sm text-zinc-900 dark:text-white hover:text-green-600 transition">{{ $comment->user->name }}</a>
                                <span class="text-xs text-zinc-400 dark:text-zinc-500">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="mt-1.5 text-sm text-zinc-700 dark:text-zinc-300 leading-relaxed">{{ $comment->body }}</p>

                            {{-- Replies --}}
                            @if($comment->replies->count())
                            <div class="mt-3 ml-2 space-y-3 border-l-2 border-green-200 dark:border-green-800 pl-4">
                                @foreach($comment->replies as $reply)
                                <div class="py-1">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('profiles.show', $reply->user) }}" class="font-semibold text-xs text-zinc-900 dark:text-white hover:text-green-600 transition">{{ $reply->user->name }}</a>
                                        <span class="text-[11px] text-zinc-400 dark:text-zinc-500">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-zinc-700 dark:text-zinc-300 mt-0.5">{{ $reply->body }}</p>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            {{-- Reply Form --}}
                            <form action="{{ route('posts.comment', $post) }}" method="POST" class="mt-3 flex gap-2 items-center">
                                @csrf
                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                <input type="text" name="body" placeholder="Reply..." class="flex-1 rounded-xl border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white focus:border-green-500 focus:ring-green-500/20 text-xs py-2">
                                <button type="submit" class="text-xs text-green-600 hover:text-green-700 font-semibold transition">Reply</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="rounded-2xl border-2 border-dashed border-zinc-200 dark:border-zinc-700 p-8 text-center">
                <span class="text-3xl">💬</span>
                <p class="text-sm text-zinc-400 mt-2">No comments yet. Start the conversation!</p>
            </div>
            @endforelse
        </div>

        {{-- Report --}}
        <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
            <details class="group">
                <summary class="px-5 py-3.5 text-xs text-zinc-400 cursor-pointer hover:text-red-500 transition flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    Report this post
                </summary>
                <form action="{{ route('reports.store') }}" method="POST" class="px-5 pb-5 space-y-3 border-t border-zinc-100 dark:border-zinc-700/60 pt-4">
                    @csrf
                    <input type="hidden" name="reportable_type" value="post">
                    <input type="hidden" name="reportable_id" value="{{ $post->id }}">
                    <select name="reason" required class="w-full rounded-xl border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white focus:border-green-500 focus:ring-green-500/20 text-sm">
                        <option value="">Select reason</option>
                        <option value="spam">Spam</option>
                        <option value="harassment">Harassment</option>
                        <option value="hate_speech">Hate Speech</option>
                        <option value="misinformation">Misinformation</option>
                        <option value="other">Other</option>
                    </select>
                    <textarea name="description" rows="2" placeholder="Additional details (optional)" class="w-full rounded-xl border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white focus:border-green-500 focus:ring-green-500/20 text-sm resize-none"></textarea>
                    <button type="submit" class="rounded-xl bg-red-600 px-4 py-2 text-xs font-semibold text-white hover:bg-red-700 transition shadow-sm">Submit Report</button>
                </form>
            </details>
        </div>
    </div>
</x-layouts::app>
