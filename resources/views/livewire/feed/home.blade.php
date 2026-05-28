<div wire:poll.30s class="min-h-screen bg-surface py-6">
    <!-- Stadium Glow Background -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-1/4 left-0 h-96 w-96 rounded-full bg-primary-container/3 blur-3xl"></div>
        <div class="absolute bottom-1/4 right-0 h-96 w-96 rounded-full bg-secondary/5 blur-3xl"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Share Modal -->
        <flux:modal name="share-post" class="min-w-[300px] md:min-w-[450px]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Share Football Talk</flux:heading>
                    <flux:subheading>Pass this on to other fans or share it to your socials.</flux:subheading>
                </div>

                <div class="grid grid-cols-4 gap-4 py-2">
                    <button
                        @click="window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent('Check out this football talk: ') + '&url=' + encodeURIComponent($wire.sharingPostUrl), '_blank')"
                        class="flex flex-col items-center gap-2 group">
                        <div
                            class="w-12 h-12 rounded-full bg-zinc-900 flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                <path
                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-on-surface-variant">X / Twitter</span>
                    </button>

                    <button
                        @click="window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent($wire.sharingPostUrl), '_blank')"
                        class="flex flex-col items-center gap-2 group">
                        <div
                            class="w-12 h-12 rounded-full bg-[#1877F2] flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-on-surface-variant">Facebook</span>
                    </button>

                    <button
                        @click="window.open('https://wa.me/?text=' + encodeURIComponent('Check out this football talk: ' + $wire.sharingPostUrl), '_blank')"
                        class="flex flex-col items-center gap-2 group">
                        <div
                            class="w-12 h-12 rounded-full bg-[#25D366] flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-on-surface-variant">WhatsApp</span>
                    </button>

                    <button
                        @click="navigator.clipboard.writeText($wire.sharingPostUrl); alert('Link copied to clipboard!')"
                        class="flex flex-col items-center gap-2 group">
                        <div
                            class="w-12 h-12 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface group-hover:scale-110 transition-transform">
                            <flux:icon icon="link" />
                        </div>
                        <span class="text-xs font-medium text-on-surface-variant">Copy Link</span>
                    </button>
                </div>

                <flux:input x-ref="shareUrl" readonly x-bind:value="$wire.sharingPostUrl" icon="link"
                    class="bg-surface-container/50">
                    <x-slot name="append">
                        <flux:button variant="ghost" size="sm"
                            @click="navigator.clipboard.writeText($wire.sharingPostUrl); alert('Copied!')">Copy
                        </flux:button>
                    </x-slot>
                </flux:input>

                <div class="flex justify-end pt-2">
                    <flux:modal.close>
                        <flux:button variant="ghost">Close</flux:button>
                    </flux:modal.close>
                </div>
            </div>
        </flux:modal>

        <!-- Welcome Header -->
        <div class="glass-card rounded-xl p-6 mb-6 relative overflow-hidden">
            <div class="absolute -top-20 -right-20 h-40 w-40 rounded-full bg-primary-container/10 blur-3xl"></div>
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-headline-lg text-on-surface flex items-center gap-3">
                        <span
                            class="w-12 h-12 rounded-xl bg-primary-container/20 flex items-center justify-center text-2xl">📰</span>
                        Football Feed
                    </h1>
                    <p class="mt-2 text-body-md text-on-surface-variant">Your daily dose of football banter, reactions,
                        and debates.</p>
                </div>
                @if (auth()->check())
                    <div class="flex items-center gap-3">
                        <div class="glass rounded-lg px-4 py-3 text-center min-w-[80px]">
                            <div class="text-headline-md text-primary-container">{{ auth()->user()->points ?? 0 }}</div>
                            <div class="text-label-sm text-on-surface-variant">Points</div>
                        </div>
                        <div class="glass rounded-lg px-4 py-3 text-center min-w-[80px]">
                            <div class="text-headline-md text-secondary">{{ auth()->user()->badges()->count() }}</div>
                            <div class="text-label-sm text-on-surface-variant">Badges</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-5">

                {{-- Create Post --}}
                @auth
                    <livewire:posts.composer mode="feed" :key="'feed-post-composer'" />
                @endauth

                <!-- Feed Posts -->
                <div class="space-y-4" id="feed-stream">
                    @forelse($feed as $post)
                        <div class="glass-card rounded-xl p-5 hover:bg-surface-container/50 transition-colors">
                            <!-- Post Header -->
                            <div class="flex items-start gap-3">
                                <a href="{{ route('profiles.show', $post->user) }}" class="flex-shrink-0">
                                    @if ($post->user->avatar_url)
                                        <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}"
                                            class="h-10 w-10 rounded-lg object-cover">
                                    @else
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-surface-container-high text-sm font-bold text-on-surface">
                                            {{ substr($post->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                </a>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <a href="{{ route('profiles.show', $post->user) }}"
                                            class="text-label-bold text-on-surface hover:text-primary-container transition-colors">
                                            {{ $post->user->name }}
                                        </a>
                                        <!-- Content Type Badge -->
                                        @if ($post->type && $post->type !== 'text')
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-primary-container/20 text-primary-container text-label-sm">
                                                {{ $this->postTypes[$post->type]['icon'] ?? '📰' }}
                                                {{ $this->postTypes[$post->type]['label'] ?? $post->type }}
                                            </span>
                                        @endif
                                        @if ($post->community)
                                            <span class="text-label-sm text-on-surface-variant">in</span>
                                            <a href="{{ route('communities.show', $post->community) }}"
                                                class="inline-flex items-center gap-1 text-label-bold text-primary-container hover:underline">
                                                {{ $post->community->name }}
                                            </a>
                                        @endif
                                        <span
                                            class="text-label-sm text-on-surface-variant">{{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Post Body -->
                            <div class="mt-3">
                                <p class="text-body-md text-on-surface leading-relaxed whitespace-pre-line">
                                    {{ $post->body }}</p>
                            </div>

                            <!-- Media -->
                            @if ($post->media)
                                <div
                                    class="mt-3 grid gap-2 {{ count($post->media) > 1 ? 'grid-cols-2' : '' }} rounded-xl overflow-hidden">
                                    @foreach ($post->media as $media)
                                        <img loading="lazy" src="{{ $media['url'] }}" alt=""
                                            class="rounded-xl max-h-72 w-full object-cover hover:opacity-90 transition-opacity">
                                    @endforeach
                                </div>
                            @endif

                            <!-- Engagement Bar -->
                            <div class="mt-4 pt-3 border-t border-outline-variant/20 flex items-center gap-1 flex-wrap">
                                <livewire:reactions.bar target-type="post" :target-id="$post->id" :key="'feed-post-reactions-' . $post->id" />

                                <!-- Comment -->
                                <a href="{{ route('posts.show', $post) }}"
                                    class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-label-sm font-medium text-on-surface-variant hover:text-primary-container hover:bg-primary-container/5 transition-all">
                                    <span class="text-base">💬</span>
                                    <span>{{ $post->comments_count }}</span>
                                </a>

                                <!-- Share -->
                                <button wire:click="sharePost({{ $post->id }})"
                                    class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-label-sm font-medium text-on-surface-variant hover:text-primary-container hover:bg-primary-container/5 transition-all">
                                    <span class="text-base">🔗</span>
                                    <span>{{ $post->shares_count }}</span>
                                </button>

                            </div>
                        </div>
                    @empty
                        <div class="glass-card rounded-xl p-8 text-center">
                            <div class="text-4xl mb-4">⚽</div>
                            <h3 class="text-headline-md text-on-surface mb-2">Your feed is empty</h3>
                            <p class="text-body-md text-on-surface-variant mb-4">Follow users or join communities to
                                see football banter here!</p>
                            <a href="{{ route('communities.index') }}"
                                class="inline-flex items-center gap-2 rounded-xl bg-primary-container px-5 py-2.5 text-sm font-semibold text-on-primary-container hover:bg-primary-container/90 transition-all">
                                Explore Communities →
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="space-y-5">
                <!-- Live Matches Widget -->
                <livewire:matches.live lazy />

                <!-- Active Polls -->
                @if ($activePolls->count())
                    <div class="glass-card rounded-xl p-4">
                        <h3 class="text-label-bold text-on-surface mb-3 flex items-center gap-2">
                            <span class="text-base">📊</span> Active Polls
                        </h3>
                        <div class="space-y-2">
                            @foreach ($activePolls as $poll)
                                <a href="{{ route('polls.show', $poll) }}"
                                    class="block p-3 rounded-lg bg-surface-container/30 hover:bg-surface-container/50 transition-colors">
                                    <div class="text-sm font-medium text-on-surface mb-1">{{ $poll->title }}</div>
                                    <div class="flex items-center gap-2 text-label-sm text-on-surface-variant">
                                        <span>{{ $poll->total_votes }} votes</span>
                                        @if ($poll->closes_at)
                                            <span>·</span>
                                            <span>Closes {{ $poll->closes_at->diffForHumans() }}</span>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Trending Topics -->
                <div class="glass-card rounded-xl p-4">
                    <h3 class="text-label-bold text-on-surface mb-3 flex items-center gap-2">
                        <span class="text-base">🔥</span> Trending
                    </h3>
                    <div class="space-y-2">
                        @foreach ($trendingTopics as $topic)
                            <a href="{{ route('search', ['q' => $topic['tag']]) }}"
                                class="flex items-center justify-between p-2 rounded-lg hover:bg-surface-container/30 transition-colors">
                                <span class="text-sm font-medium text-primary-container">{{ $topic['tag'] }}</span>
                                <span class="text-label-sm text-on-surface-variant">{{ $topic['count'] }} posts</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="glass-card rounded-xl p-4">
                    <h3 class="text-label-bold text-on-surface mb-3">Quick Stats</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-on-surface-variant">Total Posts</span>
                            <span class="text-on-surface font-bold">{{ $feed->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-on-surface-variant">Active Polls</span>
                            <span class="text-on-surface font-bold">{{ $activePolls->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
