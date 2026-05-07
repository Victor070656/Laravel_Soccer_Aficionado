<div wire:poll.30s class="min-h-screen bg-surface py-6">
    <!-- Stadium Glow Background -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-1/4 left-0 h-96 w-96 rounded-full bg-primary-container/3 blur-3xl"></div>
        <div class="absolute bottom-1/4 right-0 h-96 w-96 rounded-full bg-secondary/5 blur-3xl"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Welcome Header -->
        <div class="glass-card rounded-xl p-6 mb-6 relative overflow-hidden">
            <div class="absolute -top-20 -right-20 h-40 w-40 rounded-full bg-primary-container/10 blur-3xl"></div>
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-headline-lg text-on-surface flex items-center gap-3">
                        <span class="w-12 h-12 rounded-xl bg-primary-container/20 flex items-center justify-center text-2xl">📰</span>
                        Football Feed
                    </h1>
                    <p class="mt-2 text-body-md text-on-surface-variant">Your daily dose of football banter, reactions, and debates.</p>
                </div>
                @if(auth()->check())
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
            <!-- Main Feed -->
            <div class="lg:col-span-2 space-y-5">
                <!-- Create Post - Football First -->
                @if(auth()->check())
                    <div class="glass-card rounded-xl p-5">
                        <!-- Post Type Selector -->
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach($this->postTypes as $type => $config)
                                <button wire:click="$set('newPostType', '{{ $type }}')"
                                        class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium transition-all
                                            {{ $newPostType === $type
                                                ? 'bg-primary-container text-on-primary-container scale-105'
                                                : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container'"
                                        title="{{ $config['label'] }}">
                                    <span class="text-base">{{ $config['icon'] }}</span>
                                    <span class="hidden sm:inline">{{ $config['label'] }}</span>
                                </button>
                            @endforeach
                        </div>

                        <!-- Post Form -->
                        <form wire:submit="postAction">
                            <textarea wire:model="newPostBody"
                                      rows="3"
                                      placeholder="@if($newPostType === 'match_reaction')React to a match... ⚽🔥 @elseif($newPostType === 'banter')Share your banter... 😂 @elseif($newPostType === 'tactical_opinion')Share your tactical take... 📋 @elseif($newPostType === 'meme')Drop a meme caption... 😂 @elseWhat's on your mind about football? 🏟️@endif"
                                      class="w-full rounded-lg bg-surface-container-high border border-outline-variant/40 text-on-surface placeholder-on-surface-variant/50 p-4 text-body-md focus:border-primary-container focus:ring-1 focus:ring-primary-container/20 resize-none"></textarea>

                            <div class="mt-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="text-label-sm text-on-surface-variant">
                                        {{ strlen($newPostBody) }}/280
                                    </span>
                                    @if($newPostType === 'match_reaction' || $newPostType === 'goal_reaction')
                                        <span class="badge-live !py-0.5 !px-2 text-xs">LIVE MATCH</span>
                                    @endif
                                </div>
                                <button type="submit"
                                        class="rounded-xl bg-primary-container px-5 py-2.5 text-sm font-semibold text-on-primary-container hover:bg-primary-container/90 transition-all disabled:opacity-50"
                                        disabled="{{ strlen($newPostBody) < 1 }}">
                                    Post {{ $this->postTypes[$newPostType]['icon'] ?? '📰' }}
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- Feed Posts -->
                <div class="space-y-4" id="feed-stream">
                    @forelse($feed as $post)
                        <div class="glass-card rounded-xl p-5 hover:bg-surface-container/50 transition-colors">
                            <!-- Post Header -->
                            <div class="flex items-start gap-3">
                                <a href="{{ route('profiles.show', $post->user) }}" class="flex-shrink-0">
                                    @if($post->user->avatar_url)
                                        <img src="{{ $post->user->avatar_url }}"
                                             alt="{{ $post->user->name }}"
                                             class="h-10 w-10 rounded-lg object-cover">
                                    @else
                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-surface-container-high text-sm font-bold text-on-surface">
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
                                        @if($post->type && $post->type !== 'text')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-primary-container/20 text-primary-container text-label-sm">
                                                {{ $this->postTypes[$post->type]['icon'] ?? '📰' }}
                                                {{ $this->postTypes[$post->type]['label'] ?? $post->type }}
                                            </span>
                                        @endif
                                        @if($post->community)
                                            <span class="text-label-sm text-on-surface-variant">in</span>
                                            <a href="{{ route('communities.show', $post->community) }}"
                                               class="inline-flex items-center gap-1 text-label-bold text-primary-container hover:underline">
                                                {{ $post->community->name }}
                                            </a>
                                        @endif
                                        <span class="text-label-sm text-on-surface-variant">{{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Post Body -->
                            <div class="mt-3">
                                <p class="text-body-md text-on-surface leading-relaxed whitespace-pre-line">{{ $post->body }}</p>
                            </div>

                            <!-- Media -->
                            @if($post->media)
                                <div class="mt-3 grid gap-2 {{ count($post->media) > 1 ? 'grid-cols-2' : '' }} rounded-xl overflow-hidden">
                                    @foreach($post->media as $media)
                                        <img loading="lazy" src="{{ $media['url'] }}" alt="" class="rounded-xl max-h-72 w-full object-cover hover:opacity-90 transition-opacity">
                                    @endforeach
                                </div>
                            @endif

                            <!-- Engagement Bar -->
                            <div class="mt-4 pt-3 border-t border-outline-variant/20 flex items-center gap-1">
                                <!-- Like -->
                                <button wire:click="likePost({{ $post->id }})"
                                        class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-label-sm font-medium transition-all
                                            {{ auth()->check() && auth()->user()->hasLiked($post) ? 'text-primary-container bg-primary-container/10' : 'text-on-surface-variant hover:text-primary-container hover:bg-primary-container/5' }}">
                                    <span class="text-base">♥</span>
                                    <span>{{ $post->likes_count }}</span>
                                </button>

                                <!-- Comment -->
                                <a href="{{ route('posts.show', $post) }}"
                                   class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-label-sm font-medium text-on-surface-variant hover:text-primary-container hover:bg-primary-container/5 transition-all">
                                    <span class="text-base">💬</span>
                                    <span>{{ $post->comments_count }}</span>
                                </a>

                                <!-- Share -->
                                <span class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-label-sm font-medium text-on-surface-variant">
                                    <span class="text-base">🔗</span>
                                    <span>{{ $post->shares_count }}</span>
                                </span>

                                <!-- Quick Reactions -->
                                <div class="hidden sm:flex items-center gap-1 ml-auto">
                                    <button class="hover:scale-125 transition-transform text-lg" title="Fire">🔥</button>
                                    <button class="hover:scale-125 transition-transform text-lg" title="Love">💚</button>
                                    <button class="hover:scale-125 transition-transform text-lg" title="LOL">😂</button>
                                    <button class="hover:scale-125 transition-transform text-lg" title="Shock">😱</button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="glass-card rounded-xl p-8 text-center">
                            <div class="text-4xl mb-4">⚽</div>
                            <h3 class="text-headline-md text-on-surface mb-2">Your feed is empty</h3>
                            <p class="text-body-md text-on-surface-variant mb-4">Follow users or join communities to see football banter here!</p>
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
                @if($activePolls->count())
                    <div class="glass-card rounded-xl p-4">
                        <h3 class="text-label-bold text-on-surface mb-3 flex items-center gap-2">
                            <span class="text-base">📊</span> Active Polls
                        </h3>
                        <div class="space-y-2">
                            @foreach($activePolls as $poll)
                                <a href="{{ route('polls.show', $poll) }}"
                                   class="block p-3 rounded-lg bg-surface-container/30 hover:bg-surface-container/50 transition-colors">
                                    <div class="text-sm font-medium text-on-surface mb-1">{{ $poll->title }}</div>
                                    <div class="flex items-center gap-2 text-label-sm text-on-surface-variant">
                                        <span>{{ $poll->total_votes }} votes</span>
                                        @if($poll->closes_at)
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
                        @foreach($trendingTopics as $topic)
                            <a href="#" class="flex items-center justify-between p-2 rounded-lg hover:bg-surface-container/30 transition-colors">
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
