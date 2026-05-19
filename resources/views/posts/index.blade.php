<x-layouts::app :title="__('Feed')">
    <div class="max-w-3xl mx-auto space-y-8 p-2 sm:p-4">
        {{-- Header --}}
        <div class="relative rounded-2xl overflow-hidden bg-gradient-stadium-primary p-6 sm:p-8 text-on-primary shadow-xl shadow-primary/20 glow-primary-lg">
            <div class="absolute inset-0 opacity-10" style="background-image: url('https://images.unsplash.com/photo-1508098682722-e99c43a406b2?w=600&q=30'); background-size: cover; background-position: center;"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="relative z-10">
                <h1 class="text-h2-mobile sm:text-h1 font-bold mb-2 flex items-center gap-3 text-on-primary uppercase tracking-wide">
                    <span class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl glow-primary">📰</span>
                    Feed
                </h1>
                <p class="text-on-primary/70 text-sm sm:text-base font-medium">Share your thoughts, discuss matches, and connect with fellow fans.</p>
            </div>
        </div>

        {{-- Create Post --}}
        @auth
        <div class="card card-post rounded-2xl border border-primary/20 bg-gradient-to-b from-surface-container/80 to-surface-container/40 shadow-sm overflow-hidden glass-premium glow-primary">
            <div class="px-5 py-3.5 border-b border-primary/15 bg-gradient-to-r from-primary/15 to-primary/8">
                <h3 class="font-bold text-h6 text-on-surface flex items-center gap-2 uppercase tracking-wide">
                    <span class="w-7 h-7 rounded-lg bg-gradient-to-br from-primary/20 to-primary/10 flex items-center justify-center text-primary text-xs glow-primary">✍️</span>
                    Create Post
                </h3>
            </div>
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="p-5">
                @csrf
                <div class="flex items-start gap-3">
                    @if(auth()->user()->avatar)
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="flex-shrink-0 w-10 h-10 rounded-full object-cover shadow-md glow-primary">
                    @else
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-primary to-primary/60 flex items-center justify-center text-on-primary font-bold text-sm shadow-md glow-primary">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    @endif
                    <div class="flex-1">
                        <textarea name="body" rows="3" placeholder="Share your football thoughts... ⚽" class="w-full rounded-xl p-4 border border-primary/10 bg-surface/60 dark:bg-surface/60 dark:text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/30 text-sm resize-none transition-all" required></textarea>
                        @error('body') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                        <div class="flex items-center justify-between mt-3">
                            <label class="inline-flex items-center gap-2 text-xs text-on-surface-variant hover:text-primary cursor-pointer transition-all px-3 py-2 rounded-lg hover:bg-primary/10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Add Media
                                <input type="file" name="media[]" multiple accept="image/*,video/*" class="hidden">
                            </label>
                            <button type="submit" class="rounded-xl bg-gradient-to-r from-primary to-primary/80 px-6 py-3 text-sm font-bold text-on-primary hover:from-primary/90 hover:to-primary/70 transition-all shadow-md shadow-primary/20 hover:shadow-lg hover:scale-105 glow-primary uppercase tracking-wide">Post</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @endauth

        {{-- Posts Feed --}}
        <div class="space-y-5">
            @forelse($posts as $post)
            <div class="card card-post rounded-2xl border border-primary/15 bg-gradient-to-b from-surface-container/80 to-surface-container/40 shadow-sm hover:shadow-card-lg transition-all duration-300 overflow-hidden glass-premium hover:glow-primary">
                <div class="p-5">
                    <div class="flex items-start gap-3">
                        @if($post->user->avatar)
                        <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}" class="flex-shrink-0 w-10 h-10 rounded-full object-cover shadow-sm">
                        @else
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-primary to-primary/60 flex items-center justify-center text-on-primary font-bold text-sm shadow-sm">
                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                        </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <a href="{{ route('profiles.show', $post->user) }}" class="font-semibold text-sm text-on-surface hover:text-primary transition">{{ $post->user->name }}</a>
                                @if($post->community)
                                <span class="text-xs text-on-surface-variant">in</span>
                                <a href="{{ route('communities.show', $post->community) }}" class="inline-flex items-center gap-1 text-xs font-medium text-on-primary bg-primary/80 hover:bg-primary transition px-2 py-0.5 rounded-full">{{ $post->community->name }}</a>
                                @endif
                                <span class="text-xs text-on-surface-variant dark:text-on-surface-variant">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                            <a href="{{ route('posts.show', $post) }}" class="block mt-2">
                                <p class="text-sm text-on-surface whitespace-pre-line leading-relaxed">{{ $post->body }}</p>
                            </a>

                            @if($post->media)
                            <div class="mt-3 grid gap-2 {{ count($post->media) > 1 ? 'grid-cols-2' : '' }}">
                                @foreach($post->media as $media)
                                <img loading="lazy" decoding="async" src="{{ $media['url'] }}" alt="" class="rounded-xl max-h-64 w-full object-cover">
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-5 px-5 py-3 border-t border-primary/10 bg-surface-container-high/30">
                    @auth
                    <form action="{{ route('posts.like', $post) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="reaction-button inline-flex items-center gap-1.5 text-xs font-bold {{ auth()->user()->hasLiked($post) ? 'text-on-primary bg-gradient-to-r from-primary/80 to-primary/60 glow-primary scale-105' : 'text-on-surface-variant hover:text-primary hover:bg-primary/15 hover:glow-primary hover:scale-105' }} transition-all duration-200 px-3 py-2 rounded-lg">
                            <span class="text-base">♥</span> {{ $post->likes_count }}
                        </button>
                    </form>
                    @endauth
                    <a href="{{ route('posts.show', $post) }}" class="reaction-button inline-flex items-center gap-1.5 text-xs font-bold text-on-surface-variant hover:text-primary px-3 py-2 rounded-lg hover:bg-tertiary/15 transition-all duration-200 hover:glow-primary hover:scale-105">
                        <span class="text-base">💬</span> {{ $post->comments_count }}
                    </a>
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-on-surface-variant px-3 py-2">
                        <span class="text-base">🔗</span> {{ $post->shares_count }}
                    </span>
                </div>
            </div>

            {{-- Inject feed ad after every 5th post --}}
            @if($loop->iteration % 5 === 0 && !$loop->last)
            <x-ad-unit placement="feed" />
            @endif

            @empty
            <div class="rounded-2xl border-2 border-dashed border-primary/30 p-12 text-center glass-premium hover:glow-primary transition-all">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-primary/10 flex items-center justify-center animate-bounce">
                    <span class="text-3xl">📝</span>
                </div>
                <h3 class="font-bold text-h5 text-on-surface mb-1 uppercase tracking-wide">No Posts Yet</h3>
                <p class="text-sm text-on-surface-variant font-medium">Be the first to share something with the community!</p>
            </div>
            @endforelse
        </div>

        {{ $posts->links() }}
    </div>
</x-layouts::app>
