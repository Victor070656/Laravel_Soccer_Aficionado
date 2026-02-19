<x-layouts::app :title="__('Feed')">
    <div class="max-w-3xl mx-auto space-y-8 p-2 sm:p-4">
        {{-- Header --}}
        <div class="relative rounded-2xl overflow-hidden bg-gradient-to-r from-green-700 via-emerald-600 to-teal-600 p-6 sm:p-8 text-white shadow-xl">
            <div class="absolute inset-0 opacity-10" style="background-image: url('https://images.unsplash.com/photo-1508098682722-e99c43a406b2?w=600&q=30'); background-size: cover; background-position: center;"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="relative z-10">
                <h1 class="text-2xl sm:text-3xl font-bold mb-2 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl">📰</span>
                    Feed
                </h1>
                <p class="text-green-100 text-sm sm:text-base">Share your thoughts, discuss matches, and connect with fellow fans.</p>
            </div>
        </div>

        {{-- Create Post --}}
        @auth
        <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-zinc-100 dark:border-zinc-700/60 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/10 dark:to-emerald-900/10">
                <h3 class="font-semibold text-zinc-900 dark:text-white flex items-center gap-2 text-sm">
                    <span class="w-7 h-7 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 text-xs">✍️</span>
                    Create Post
                </h3>
            </div>
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="p-5">
                @csrf
                <div class="flex items-start gap-3">
                    @if(auth()->user()->avatar)
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="flex-shrink-0 w-10 h-10 rounded-full object-cover shadow-sm">
                    @else
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    @endif
                    <div class="flex-1">
                        <textarea name="body" rows="3" placeholder="Share your thoughts..." class="w-full rounded-xl p-4 border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white focus:border-green-500 focus:ring-green-500/20 text-sm resize-none" required></textarea>
                        @error('body') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <div class="flex items-center justify-between mt-3">
                            <label class="inline-flex items-center gap-2 text-xs text-zinc-400 hover:text-green-600 cursor-pointer transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Add Media
                                <input type="file" name="media[]" multiple accept="image/*,video/*" class="hidden">
                            </label>
                            <button type="submit" class="rounded-xl bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-2.5 text-sm font-semibold text-white hover:from-green-500 hover:to-emerald-400 transition-all shadow-md shadow-green-600/20 hover:scale-105">Post</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @endauth

        {{-- Posts Feed --}}
        <div class="space-y-5">
            @forelse($posts as $post)
            <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                <div class="p-5">
                    <div class="flex items-start gap-3">
                        @if($post->user->avatar)
                        <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}" class="flex-shrink-0 w-10 h-10 rounded-full object-cover shadow-sm">
                        @else
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                        </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <a href="{{ route('profiles.show', $post->user) }}" class="font-semibold text-sm text-zinc-900 dark:text-white hover:text-green-600 transition">{{ $post->user->name }}</a>
                                @if($post->community)
                                <span class="text-xs text-zinc-400">in</span>
                                <a href="{{ route('communities.show', $post->community) }}" class="inline-flex items-center gap-1 text-xs font-medium text-green-600 hover:text-green-700 transition bg-green-50 dark:bg-green-900/20 px-2 py-0.5 rounded-full">{{ $post->community->name }}</a>
                                @endif
                                <span class="text-xs text-zinc-400 dark:text-zinc-500">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                            <a href="{{ route('posts.show', $post) }}" class="block mt-2">
                                <p class="text-sm text-zinc-700 dark:text-zinc-300 whitespace-pre-line leading-relaxed">{{ $post->body }}</p>
                            </a>

                            @if($post->media)
                            <div class="mt-3 grid gap-2 {{ count($post->media) > 1 ? 'grid-cols-2' : '' }}">
                                @foreach($post->media as $media)
                                <img loading="lazy" decoding="async" src="{{ asset('storage/' . $media) }}" alt="" class="rounded-xl max-h-64 w-full object-cover">
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-5 px-5 py-3 border-t border-zinc-100 dark:border-zinc-700/60 bg-zinc-50/50 dark:bg-zinc-800/80">
                    @auth
                    <form action="{{ route('posts.like', $post) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center gap-1.5 text-xs font-medium {{ auth()->user()->hasLiked($post) ? 'text-green-600 font-bold' : 'text-zinc-400 hover:text-green-600' }} transition">
                            <span class="text-base">♥</span> {{ $post->likes_count }}
                        </button>
                    </form>
                    @endauth
                    <a href="{{ route('posts.show', $post) }}" class="flex items-center gap-1.5 text-xs font-medium text-zinc-400 hover:text-green-600 transition">
                        <span class="text-base">💬</span> {{ $post->comments_count }}
                    </a>
                    <span class="flex items-center gap-1.5 text-xs text-zinc-400">
                        <span class="text-base">🔗</span> {{ $post->shares_count }}
                    </span>
                </div>
            </div>

            {{-- Inject feed ad after every 5th post --}}
            @if($loop->iteration % 5 === 0 && !$loop->last)
            <x-ad-unit placement="feed" />
            @endif

            @empty
            <div class="rounded-2xl border-2 border-dashed border-zinc-200 dark:border-zinc-700 p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                    <span class="text-3xl">📝</span>
                </div>
                <h3 class="text-lg font-bold text-zinc-600 dark:text-zinc-400 mb-1">No Posts Yet</h3>
                <p class="text-sm text-zinc-400 dark:text-zinc-500">Be the first to share something with the community!</p>
            </div>
            @endforelse
        </div>

        {{ $posts->links() }}
    </div>
</x-layouts::app>
