<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-8 rounded-xl p-2 sm:p-4">

        {{-- Welcome Banner --}}
        <div class="relative rounded-2xl overflow-hidden bg-gradient-to-r from-primary via-primary/80 to-primary/60 p-6 sm:p-8 text-on-primary shadow-xl shadow-primary/20">
            <div class="absolute inset-0 opacity-10" style="background-image: url('https://images.unsplash.com/photo-1508098682722-e99c643e7f0b?w=600&q=30'); background-size: cover; background-position: center;"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/4"></div>
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}! 👋</h1>
                    <p class="text-primary/70 text-sm sm:text-base">Stay updated with the latest matches, posts, and community activity.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="glass rounded-xl px-4 py-3 text-center min-w-[80px]">
                        <div class="text-2xl font-bold">{{ auth()->user()->points ?? 0 }}</div>
                        <div class="text-xs text-primary/70">Points</div>
                    </div>
                    <div class="glass rounded-xl px-4 py-3 text-center min-w-[80px]">
                        <div class="text-2xl font-bold">{{ auth()->user()->badges()->count() }}</div>
                        <div class="text-xs text-primary/70">Badges</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Live Matches Banner --}}
        @if($liveMatches->isNotEmpty())
        <div class="rounded-2xl bg-gradient-to-r from-secondary/10 to-secondary/5 dark:from-secondary/20 dark:to-secondary/10 p-5 sm:p-6 border border-secondary/30 dark:border-secondary/20 shadow-lg shadow-secondary/5">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex items-center gap-2 bg-secondary text-on-secondary px-3 py-1.5 rounded-full text-sm font-bold shadow-lg shadow-secondary/30">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-current opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-current"></span>
                    </span>
                    LIVE
                </div>
                <h2 class="text-lg font-bold text-secondary">Live Matches</h2>
                <a href="{{ route('matches.live') }}" class="ml-auto text-sm text-secondary hover:text-secondary/80 font-medium">View All →</a>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($liveMatches as $match)
                <a href="{{ route('matches.show', $match->id) }}" class="group block rounded-xl bg-surface-container dark:bg-surface-container p-4 shadow-md hover:shadow-xl transition-all duration-300 border border-outline-variant/20 dark:border-outline-variant/30 hover:-translate-y-1 glass-edge">
                    <div class="flex items-center gap-1.5 text-xs text-on-surface-variant mb-2">
                        @if($match->league['logo'] ?? null)
                        <img loading="lazy" decoding="async" src="{{ $match->league['logo'] }}" alt="" class="h-4 w-4 object-contain">
                        @endif
                        <span class="font-medium">{{ $match->league['name'] }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 flex-1 min-w-0">
                            @if($match->home_team['logo'] ?? null)
                            <img loading="lazy" decoding="async" src="{{ $match->home_team['logo'] }}" alt="" class="h-6 w-6 object-contain flex-shrink-0">
                            @endif
                            <span class="font-semibold text-sm truncate">{{ $match->home_team['name'] }}</span>
                        </div>
                        <div class="flex-shrink-0 bg-gradient-to-r from-primary to-primary/70 text-on-primary font-bold text-lg px-3 py-1 rounded-lg shadow-sm">{{ $match->score_display }}</div>
                        <div class="flex items-center gap-2 flex-1 min-w-0 justify-end">
                            <span class="font-semibold text-sm truncate">{{ $match->away_team['name'] }}</span>
                            @if($match->away_team['logo'] ?? null)
                            <img loading="lazy" decoding="async" src="{{ $match->away_team['logo'] }}" alt="" class="h-6 w-6 object-contain flex-shrink-0">
                            @endif
                        </div>
                    </div>
                    <div class="text-center mt-2">
                        <span class="inline-flex items-center gap-1.5 text-xs text-secondary font-medium bg-secondary/10 dark:bg-secondary/20 px-2 py-0.5 rounded-full">
                            <span class="relative flex h-1.5 w-1.5"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span><span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-secondary"></span></span>
                            {{ ucfirst(str_replace('_', ' ', $match->status)) }}@if($match->elapsed) · {{ $match->elapsed }}'@endif
                        </span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <div class="grid gap-8 lg:grid-cols-3">
            {{-- Main Feed --}}
            <div class="lg:col-span-2 space-y-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-on-surface flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary text-sm">📰</span>
                        Your Feed
                    </h2>
                    <a href="{{ route('posts.index') }}" class="text-sm text-primary hover:text-primary/80 font-medium hover:underline">View All →</a>
                </div>

                {{-- Create Post --}}
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="rounded-2xl border border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container p-5 shadow-sm hover:shadow-md transition-shadow glass-edge">
                    @csrf
                    <div class="flex items-start gap-3">
                        @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="flex-shrink-0 w-10 h-10 rounded-full object-cover shadow-md">
                        @else
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-primary to-primary/60 flex items-center justify-center text-on-primary font-bold text-sm shadow-md">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        @endif
                        <div class="flex-1">
                            <textarea name="body" rows="3" placeholder="What's on your mind about football? ⚽" class="w-full rounded-xl p-4 border-outline-variant/20 bg-surface dark:border-outline-variant/30 dark:bg-surface text-on-surface focus:border-primary focus:ring-primary/20 text-sm resize-none placeholder:text-on-surface-variant"></textarea>
                            <div class="flex items-center justify-between mt-3">
                                <label class="inline-flex items-center gap-2 text-xs text-on-surface-variant hover:text-primary cursor-pointer transition-colors px-3 py-2 rounded-lg hover:bg-primary/10 dark:hover:bg-primary/10">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Add Media
                                    <input type="file" name="media[]" multiple accept="image/*,video/*" class="hidden">
                                </label>
                                <button type="submit" class="rounded-xl bg-gradient-to-r from-primary to-primary/80 px-6 py-2 text-sm font-semibold text-on-primary hover:from-primary/90 hover:to-primary/70 transition-all duration-300 shadow-md shadow-primary/20 hover:shadow-lg hover:shadow-primary/30 hover:scale-105">
                                    Post
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- Feed Posts --}}
                @forelse($feed as $post)
                <div class="rounded-2xl border border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container p-5 shadow-sm hover:shadow-md transition-all duration-300 group glass-edge">
                    <div class="flex items-start gap-3">
                        <a href="{{ route('profiles.show', $post->user) }}" class="flex-shrink-0 w-11 h-11 rounded-full hover:scale-110 transition-transform">
                            @if($post->user->avatar)
                            <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}" class="w-11 h-11 rounded-full object-cover shadow-md">
                            @else
                            <div class="w-11 h-11 rounded-full bg-gradient-to-br from-primary to-primary/60 flex items-center justify-center text-on-primary font-bold text-sm shadow-md">
                                {{ strtoupper(substr($post->user->name, 0, 1)) }}
                            </div>
                            @endif
                        </a>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <a href="{{ route('profiles.show', $post->user) }}" class="font-bold text-sm text-on-surface hover:text-primary dark:hover:text-primary transition-colors">{{ $post->user->name }}</a>
                                @if($post->community)
                                <span class="text-xs text-on-surface-variant">in</span>
                                <a href="{{ route('communities.show', $post->community) }}" class="inline-flex items-center gap-1 text-xs font-medium text-on-primary bg-primary/80 px-2 py-0.5 rounded-full hover:bg-primary transition-colors">{{ $post->community->name }}</a>
                                @endif
                                <span class="text-xs text-on-surface-variant">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="mt-2 text-sm text-on-surface leading-relaxed">{{ $post->body }}</p>

                            @if($post->media)
                            <div class="mt-3 grid gap-2 {{ count($post->media) > 1 ? 'grid-cols-2' : '' }} rounded-xl overflow-hidden">
                                @foreach($post->media as $media)
                                <img loading="lazy" decoding="async" src="{{ $media['url'] }}" alt="" class="rounded-xl max-h-72 w-full object-cover hover:opacity-90 transition-opacity">
                                @endforeach
                            </div>
                            @endif

                            <div class="flex items-center gap-1 mt-4 pt-3 border-t border-outline-variant/20 dark:border-outline-variant/30">
                                <form action="{{ route('posts.like', $post) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-2 rounded-lg {{ auth()->user()->hasLiked($post) ? 'text-on-primary bg-primary/80' : 'text-on-surface-variant hover:text-primary hover:bg-primary/10 dark:hover:bg-primary/10' }} transition-all">
                                        <svg class="w-4 h-4" fill="{{ auth()->user()->hasLiked($post) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                        {{ $post->likes_count }}
                                    </button>
                                </form>
                                <a href="{{ route('posts.show', $post) }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-on-surface-variant hover:text-primary px-3 py-2 rounded-lg hover:bg-primary/10 dark:hover:bg-primary/10 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    {{ $post->comments_count }}
                                </a>
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-on-surface-variant px-3 py-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                                    {{ $post->shares_count }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="rounded-2xl border-2 border-dashed border-outline-variant/30 dark:border-outline-variant/40 p-12 text-center">
                    <div class="text-5xl mb-4">⚽</div>
                    <h3 class="font-bold text-on-surface mb-2">Your feed is empty</h3>
                    <p class="text-sm text-on-surface-variant mb-4">Follow some users or join communities to build your feed!</p>
                    <a href="{{ route('communities.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-primary to-primary/80 px-6 py-2.5 text-sm text-on-primary font-semibold hover:from-primary/90 hover:to-primary/70 transition-all shadow-md">
                        Explore Communities
                    </a>
                </div>
                @endforelse
            </div>

            {{-- Sidebar --}}
            <div class="space-y-5">
                {{-- Upcoming Matches --}}
                <div class="rounded-2xl border border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container overflow-hidden shadow-sm glass-edge">
                    <div class="px-5 py-4 bg-gradient-to-r from-primary/10 to-primary/5 dark:from-primary/15 dark:to-primary/10 border-b border-outline-variant/20 dark:border-outline-variant/30">
                        <h3 class="font-bold text-sm text-on-surface flex items-center gap-2">
                            <span class="text-base">📅</span> Upcoming Matches
                        </h3>
                    </div>
                    <div class="p-3">
                        @forelse($upcomingMatches as $match)
                        <a href="{{ route('matches.show', $match->id) }}" class="block p-3 rounded-xl hover:bg-primary/5 dark:hover:bg-primary/10 transition-all duration-200 group/match">
                            <div class="flex items-center gap-1.5 text-xs text-on-surface-variant mb-1.5">
                                @if($match->league['logo'] ?? null)
                                <img loading="lazy" decoding="async" src="{{ $match->league['logo'] }}" alt="" class="h-3.5 w-3.5 object-contain">
                                @endif
                                <span>{{ \Carbon\Carbon::parse($match->date)->format('M d, H:i') }}</span>
                                <span class="text-outline-variant/40 dark:text-outline-variant/30">·</span>
                                <span>{{ $match->league['name'] }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm font-medium text-on-surface">
                                <div class="flex items-center gap-1.5">
                                    @if($match->home_team['logo'] ?? null)
                                    <img loading="lazy" decoding="async" src="{{ $match->home_team['logo'] }}" alt="" class="h-5 w-5 object-contain">
                                    @endif
                                    <span class="group-hover/match:text-primary transition-colors">{{ $match->home_team['name'] }}</span>
                                </div>
                                <span class="text-xs font-normal text-on-surface-variant bg-surface-container-high dark:bg-surface-container-high px-2 py-0.5 rounded-md">vs</span>
                                <div class="flex items-center gap-1.5">
                                    <span class="group-hover/match:text-primary transition-colors">{{ $match->away_team['name'] }}</span>
                                    @if($match->away_team['logo'] ?? null)
                                    <img loading="lazy" decoding="async" src="{{ $match->away_team['logo'] }}" alt="" class="h-5 w-5 object-contain">
                                    @endif
                                </div>
                            </div>
                        </a>
                        @empty
                        <p class="text-sm text-on-surface-variant p-3 text-center">No upcoming matches.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Active Polls --}}
                <div class="rounded-2xl border border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container overflow-hidden shadow-sm glass-edge">
                    <div class="px-5 py-4 bg-gradient-to-r from-tertiary/10 to-tertiary/5 dark:from-tertiary/15 dark:to-tertiary/10 border-b border-outline-variant/20 dark:border-outline-variant/30">
                        <h3 class="font-bold text-sm text-on-surface flex items-center gap-2">
                            <span class="text-base">📊</span> Active Polls
                        </h3>
                    </div>
                    <div class="p-3">
                        @forelse($activePolls as $poll)
                        <a href="{{ route('polls.show', $poll) }}" class="block p-3 rounded-xl hover:bg-tertiary/5 dark:hover:bg-tertiary/10 transition-all duration-200">
                            <div class="text-sm font-medium text-on-surface mb-1.5">{{ $poll->title }}</div>
                            <div class="flex justify-between text-xs text-on-surface-variant">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                    {{ $poll->total_votes }} votes
                                </span>
                                @if($poll->closes_at)
                                <span>Closes {{ $poll->closes_at->diffForHumans() }}</span>
                                @endif
                            </div>
                        </a>
                        @empty
                        <p class="text-sm text-on-surface-variant p-3 text-center">No active polls.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Sidebar Ad --}}
                <x-ad-unit placement="sidebar" />

                {{-- Trending Posts --}}
                <div class="rounded-2xl border border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container overflow-hidden shadow-sm glass-edge">
                    <div class="px-5 py-4 bg-gradient-to-r from-secondary/10 to-secondary/5 dark:from-secondary/15 dark:to-secondary/10 border-b border-outline-variant/20 dark:border-outline-variant/30">
                        <h3 class="font-bold text-sm text-on-surface flex items-center gap-2">
                            <span class="text-base">🔥</span> Trending
                        </h3>
                    </div>
                    <div class="p-3">
                        @forelse($trendingPosts as $post)
                        <a href="{{ route('posts.show', $post) }}" class="block p-3 rounded-xl hover:bg-secondary/5 dark:hover:bg-secondary/10 transition-all duration-200">
                            <div class="text-sm text-on-surface line-clamp-2 leading-relaxed">{{ Str::limit($post->body, 80) }}</div>
                            <div class="flex items-center gap-2 mt-2 text-xs text-on-surface-variant">
                                <span class="font-medium">{{ $post->user->name }}</span>
                                <span class="text-outline-variant/40">·</span>
                                <span class="inline-flex items-center gap-1 text-primary">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    {{ $post->likes_count }}
                                </span>
                            </div>
                        </a>
                        @empty
                        <p class="text-sm text-on-surface-variant p-3 text-center">Nothing trending yet.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Favorite Clubs --}}
                @if($favoriteClubs->isNotEmpty())
                <div class="rounded-2xl border border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container overflow-hidden shadow-sm glass-edge">
                    <div class="px-5 py-4 bg-gradient-to-r from-primary/10 to-primary/5 dark:from-primary/15 dark:to-primary/10 border-b border-outline-variant/20 dark:border-outline-variant/30">
                        <h3 class="font-bold text-sm text-on-surface flex items-center gap-2">
                            <span class="text-base">💚</span> Your Clubs
                        </h3>
                    </div>
                    <div class="p-4">
                        <div class="flex flex-wrap gap-2">
                            @foreach($favoriteClubs as $club)
                            <a href="{{ $club->api_team_id ? route('clubs.show', $club->api_team_id) : '#' }}" class="inline-flex items-center gap-1.5 rounded-full bg-gradient-to-r from-primary/10 to-primary/5 dark:from-primary/15 dark:to-primary/10 px-4 py-2 text-xs font-medium text-on-primary hover:from-primary/20 hover:to-primary/10 dark:hover:from-primary/25 dark:hover:to-primary/15 transition-all border border-primary/20 dark:border-primary/30 hover:scale-105">
                                @if($club->logo_url)<img src="{{ $club->logo_url }}" alt="" class="w-4 h-4 object-contain">@endif
                                {{ $club->name }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts::app>
