<div wire:poll.60s class="min-h-screen bg-surface py-6">
    <!-- Stadium Glow Background -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-0 left-1/4 h-96 w-96 rounded-full bg-primary-container/5 blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 h-96 w-96 rounded-full bg-secondary/5 blur-3xl"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="glass-card rounded-xl p-6 mb-6 relative overflow-hidden">
            <div class="absolute -top-20 -right-20 h-40 w-40 rounded-full bg-primary-container/10 blur-3xl"></div>
            <div class="relative z-10">
                <h1 class="text-headline-lg text-on-surface flex items-center gap-3">
                    <span class="w-12 h-12 rounded-xl bg-primary-container/20 flex items-center justify-center text-2xl">🔥</span>
                    Trending
                </h1>
                <p class="mt-2 text-body-md text-on-surface-variant">What's happening in the football world right now.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Trending Column -->
            <div class="lg:col-span-2 space-y-5">
                <!-- Breaking News Ticker -->
                @if($breakingNews->count())
                    <div class="glass-card rounded-xl p-4 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-error animate-pulse"></div>
                        <h3 class="text-label-bold text-on-surface mb-3 flex items-center gap-2 pl-3">
                            <span class="badge-live !py-0 !px-2">BREAKING</span> Football News
                        </h3>
                        <div class="space-y-3 pl-3">
                            @foreach($breakingNews as $news)
                                <a href="{{ route('posts.show', $news) }}"
                                   class="block p-3 rounded-lg bg-surface-container/30 hover:bg-surface-container/50 transition-colors">
                                    <p class="text-body-md text-on-surface line-clamp-2">{{ $news->body }}</p>
                                    <div class="mt-2 flex items-center gap-2 text-label-sm text-on-surface-variant">
                                        <span>{{ $news->user->name }}</span>
                                        <span>·</span>
                                        <span>{{ $news->created_at->diffForHumans() }}</span>
                                        <span>·</span>
                                        <span>♥ {{ $news->likes_count }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Trending Hashtags -->
                <div class="glass-card rounded-xl p-5">
                    <h3 class="text-headline-md text-on-surface mb-4 flex items-center gap-2">
                        <span>#</span> Trending Hashtags
                    </h3>
                    <div class="space-y-1">
                        @forelse($hashtagTrends as $trend)
                            <a href="{{ route('search', ['q' => $trend['tag']]) }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-surface-container/30 transition-all group">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-label-bold text-primary-container group-hover:text-primary-container/80">
                                            {{ $trend['tag'] }}
                                        </span>
                                        @if($trend['trend'] === 'hot')
                                            <span class="badge-live !py-0 !px-2">HOT</span>
                                        @elseif($trend['trend'] === 'rising')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-secondary/20 text-secondary text-label-sm">
                                                ↑ Rising
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-label-sm text-on-surface-variant mt-1">
                                        {{ $trend['count'] }} posts
                                    </div>
                                </div>
                                <span class="text-label-sm text-on-surface-variant opacity-0 group-hover:opacity-100 transition-opacity">
                                    Join →
                                </span>
                            </a>
                        @empty
                            <p class="text-label-sm text-on-surface-variant text-center py-4">No trending hashtags yet.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Viral Fan Debates -->
                <div class="glass-card rounded-xl p-5">
                    <h3 class="text-headline-md text-on-surface mb-4 flex items-center gap-2">
                        <span>💬</span> Viral Fan Debates
                    </h3>
                    <div class="space-y-3">
                        @forelse($viralDebates as $debate)
                            <div class="p-4 rounded-lg bg-surface-container/30 hover:bg-surface-container/50 transition-colors">
                                <div class="flex items-start gap-3">
                                    @if($debate->user->avatar_url)
                                        <img src="{{ $debate->user->avatar_url }}"
                                             alt="{{ $debate->user->name }}"
                                             class="h-8 w-8 rounded-lg object-cover">
                                    @else
                                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-surface-container-high text-xs font-bold text-on-surface">
                                            {{ substr($debate->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="text-label-bold text-on-surface">{{ $debate->user->name }}</span>
                                            @if($debate->type === 'tactical_opinion')
                                                <span class="px-2 py-0.5 rounded-full bg-tertiary-fixed-dim/20 text-tertiary-fixed-dim text-label-sm">Tactical</span>
                                            @elseif($debate->type === 'banter')
                                                <span class="px-2 py-0.5 rounded-full bg-primary-container/20 text-primary-container text-label-sm">Banter</span>
                                            @endif
                                        </div>
                                        <p class="mt-2 text-body-md text-on-surface line-clamp-3">{{ $debate->body }}</p>
                                        <div class="mt-3 flex items-center gap-4 text-label-sm text-on-surface-variant">
                                            <span class="flex items-center gap-1">♥ {{ $debate->likes_count }}</span>
                                            <span class="flex items-center gap-1">💬 {{ $debate->comments_count }}</span>
                                            <a href="{{ route('posts.show', $debate) }}"
                                               class="text-primary-container hover:underline">Join debate →</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-label-sm text-on-surface-variant text-center py-4">No viral debates right now.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Fast-Rising Conversations -->
                <div class="glass-card rounded-xl p-5">
                    <h3 class="text-headline-md text-on-surface mb-4 flex items-center gap-2">
                        <span>🚀</span> Fast-Rising Conversations
                    </h3>
                    <div class="space-y-3">
                        @forelse($risingConversations as $conv)
                            <div class="flex items-center justify-between p-3 rounded-lg hover:bg-surface-container/30 transition-colors">
                                <div class="flex-1">
                                    <p class="text-body-md text-on-surface line-clamp-2">{{ $conv->body }}</p>
                                    <div class="mt-2 flex items-center gap-3 text-label-sm text-on-surface-variant">
                                        <span>{{ $conv->user->name }}</span>
                                        <span>·</span>
                                        <span>♥ {{ $conv->likes_count }}</span>
                                        <span>💬 {{ $conv->comments_count }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('posts.show', $conv) }}"
                                   class="ml-3 px-3 py-1.5 rounded-lg bg-primary-container/20 text-primary-container text-label-sm hover:bg-primary-container/30 transition-colors">
                                    Join
                                </a>
                            </div>
                        @empty
                            <p class="text-label-sm text-on-surface-variant text-center py-4">No rising conversations.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-5">
                <!-- Most Discussed Clubs -->
                <div class="glass-card rounded-xl p-4">
                    <h3 class="text-label-bold text-on-surface mb-3 flex items-center gap-2">
                        <span>⚽</span> Most Discussed Clubs
                    </h3>
                    <div class="space-y-2">
                        @foreach($clubTrends as $index => $club)
                            <a href="{{ route('clubs.show', $club->api_team_id ?? $club->id) }}"
                               class="flex items-center gap-3 p-2 rounded-lg hover:bg-surface-container/30 transition-all">
                                <span class="text-label-bold text-on-surface-variant w-6 text-center">{{ $index + 1 }}</span>
                                @if($club->logo_url)
                                    <img src="{{ $club->logo_url }}" alt="" class="h-8 w-8 object-contain">
                                @else
                                    <div class="h-8 w-8 rounded-lg bg-surface-container-high"></div>
                                @endif
                                <div class="flex-1">
                                    <div class="text-label-bold text-on-surface">{{ $club->name }}</div>
                                    <div class="text-label-sm text-on-surface-variant">{{ $club->posts_count }} discussions</div>
                                </div>
                                @if($index < 3)
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-primary-container/20 text-primary-container">Hot</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Trending Players -->
                <div class="glass-card rounded-xl p-4">
                    <h3 class="text-label-bold text-on-surface mb-3 flex items-center gap-2">
                        <span>⚔</span> Trending Players
                    </h3>
                    <div class="space-y-2">
                        @foreach($playerTrends as $index => $player)
                            <a href="{{ route('search', ['q' => $player->name]) }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-surface-container/30 transition-all">
                                <span class="text-label-bold text-on-surface-variant w-6 text-center">{{ $index + 1 }}</span>
                                @if($player->photo_url)
                                    <img src="{{ $player->photo_url }}" alt="" class="h-8 w-8 rounded-lg object-cover">
                                @else
                                    <div class="h-8 w-8 rounded-lg bg-surface-container-high flex items-center justify-center text-xs">👤</div>
                                @endif
                                <div class="flex-1">
                                    <div class="text-label-bold text-on-surface">{{ $player->name }}</div>
                                    <div class="text-label-sm text-on-surface-variant">{{ $player->club->name ?? 'Free Agent' }}</div>
                                </div>
                                <span class="text-label-sm text-on-surface-variant">{{ $player->posts_count }} posts</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Most Active Match Rooms -->
                <div class="glass-card rounded-xl p-4">
                    <h3 class="text-label-bold text-on-surface mb-3 flex items-center gap-2">
                        <span>🔴</span> Active Match Rooms
                    </h3>
                    <div class="space-y-2">
                        @foreach($activeMatchRooms as $room)
                            <a href="{{ route('matches.room', $room->match_id) }}"
                               class="flex items-center justify-between p-2 rounded-lg hover:bg-surface-container/30 transition-all">
                                <div class="flex items-center gap-2">
                                    <span class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-container opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-container"></span>
                                    </span>
                                    <span class="text-label-bold text-on-surface">Match #{{ $room->match_id }}</span>
                                </div>
                                <span class="text-label-sm text-on-surface-variant">{{ $room->comment_count }} comments</span>
                            </a>
                        @endforeach
                        @if($activeMatchRooms->count() === 0)
                            <p class="text-label-sm text-on-surface-variant text-center py-2">No active match rooms.</p>
                        @endif
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="glass-card rounded-xl p-4">
                    <h3 class="text-label-bold text-on-surface mb-3">Quick Stats</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-on-surface-variant">Hashtags</span>
                            <span class="text-on-surface font-bold">{{ count($hashtagTrends) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-on-surface-variant">Viral Debates</span>
                            <span class="text-on-surface font-bold">{{ $viralDebates->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-on-surface-variant">Active Rooms</span>
                            <span class="text-on-surface font-bold">{{ $activeMatchRooms->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
