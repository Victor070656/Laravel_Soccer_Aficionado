<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-2 sm:p-3 md:p-4">

        {{-- Welcome Banner --}}
        <div class="relative rounded-2xl overflow-hidden bg-gradient-stadium-primary p-4 sm:p-6 md:p-8 text-on-primary shadow-xl shadow-primary/20 glow-primary-lg">
            <div class="absolute inset-0 opacity-10" style="background-image: url('https://images.unsplash.com/photo-1508098682722-e99c643e7f0b?w=600&q=30'); background-size: cover; background-position: center;"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/4"></div>
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-h2-mobile sm:text-h1 font-bold mb-2 text-on-primary">⚽ Your Football Hub</h1>
                    <p class="text-on-primary/70 text-sm sm:text-base font-medium">{{ auth()->user()->name }}, your personalized football community awaits!</p>
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <div class="glass glow-primary rounded-xl px-4 py-3 text-center min-w-[80px] hover:scale-105 transition-transform">
                        <div class="text-2xl font-bold text-primary">{{ auth()->user()->points ?? 0 }}</div>
                        <div class="text-xs text-on-primary/70 uppercase font-semibold">Points</div>
                    </div>
                    <div class="glass glow-primary rounded-xl px-4 py-3 text-center min-w-[80px] hover:scale-105 transition-transform">
                        <div class="text-2xl font-bold text-primary">{{ auth()->user()->badges()->count() }}</div>
                        <div class="text-xs text-on-primary/70 uppercase font-semibold">Badges</div>
                    </div>
                    <div class="glass glow-primary rounded-xl px-4 py-3 text-center min-w-[80px] hover:scale-105 transition-transform">
                        <div class="text-2xl font-bold text-secondary">{{ auth()->user()->following()->count() }}</div>
                        <div class="text-xs text-on-primary/70 uppercase font-semibold">Following</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Live Matches Banner --}}
        <livewire:matches.live lazy />

        {{-- FAN HUB SECTION: Your Matches & Clubs --}}
        <div class="grid gap-6 md:grid-cols-2">
            {{-- Your Primary Club Section --}}
            @if($favoriteClubs->isNotEmpty())
            <div class="card rounded-2xl border border-primary/25 bg-gradient-to-b from-primary/8 to-primary/4 p-6 shadow-sm glass-premium hover:glow-primary-sm transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">💚</span>
                        <div>
                            <h3 class="text-h6 font-bold text-on-surface uppercase tracking-wide">Your Club</h3>
                            <p class="text-xs text-on-surface-variant font-medium">Primary team</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-4 p-4 rounded-xl bg-gradient-to-r from-primary/15 to-primary/10 border border-primary/20 hover:from-primary/25 hover:to-primary/15 transition-all group cursor-pointer">
                    @php $primaryClub = $favoriteClubs->first(); @endphp
                    @if($primaryClub->logo_url)
                    <img src="{{ $primaryClub->logo_url }}" alt="{{ $primaryClub->name }}" class="w-12 h-12 object-contain group-hover:scale-110 transition-transform">
                    @endif
                    <div class="flex-1">
                        <div class="font-bold text-on-surface">{{ $primaryClub->name }}</div>
                        <div class="text-xs text-on-surface-variant font-medium">{{ $primaryClub->country ?? 'Your favorite team' }}</div>
                    </div>
                    <a href="{{ $primaryClub->api_team_id ? route('clubs.show', $primaryClub->api_team_id) : '#' }}" class="text-primary hover:text-primary/80 transition-colors">→</a>
                </div>
            </div>

            {{-- Your Upcoming Matches --}}
            <div class="card rounded-2xl border border-secondary/25 bg-gradient-to-b from-secondary/8 to-secondary/4 p-6 shadow-sm glass-premium hover:glow-primary-sm transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">📅</span>
                        <div>
                            <h3 class="text-h6 font-bold text-on-surface uppercase tracking-wide">Next Matches</h3>
                            <p class="text-xs text-on-surface-variant font-medium">Your team's fixtures</p>
                        </div>
                    </div>
                    <a href="{{ route('matches.index') }}" class="focus-ring text-primary hover:text-primary/80 font-bold text-sm transition-colors">View All</a>
                </div>
                <livewire:matches.upcoming lazy />
            </div>
            @endif
        </div>

        {{-- MAIN CONTENT GRID --}}
        <div class="grid gap-6 lg:gap-8 lg:grid-cols-3">
            {{-- Main Feed --}}
            <div class="lg:col-span-2 space-y-4 md:space-y-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-h5 font-bold text-on-surface flex items-center gap-2 uppercase tracking-wide">
                        <span class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary/20 to-primary/10 flex items-center justify-center text-primary text-lg glow-primary">📰</span>
                        Community Feed
                    </h2>
                    <a href="{{ route('posts.index') }}" class="focus-ring text-sm text-primary hover:text-primary/80 font-semibold hover:underline hover:gap-2 inline-flex items-center transition-all">View All <span class="ml-1">→</span></a>
                </div>

                {{-- Create Post --}}
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="card card-post rounded-2xl border border-primary/20 bg-gradient-to-b from-surface-container/80 to-surface-container/40 p-5 shadow-sm hover:shadow-card transition-all duration-300 glass-premium">
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
                            <textarea name="body" rows="3" placeholder="Share your football thoughts... ⚽" class="w-full rounded-xl p-4 border border-primary/10 bg-surface/60 text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/30 text-sm resize-none placeholder:text-on-surface-variant/60 transition-all"></textarea>
                            <div class="flex items-center justify-between mt-3">
                                <label class="inline-flex items-center gap-2 text-xs text-on-surface-variant hover:text-primary cursor-pointer transition-all px-3 py-2 rounded-lg hover:bg-primary/10">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Add Media
                                    <input type="file" name="media[]" multiple accept="image/*,video/*" class="hidden">
                                </label>
                                <button type="submit" class="focus-ring btn-primary rounded-xl text-sm font-bold hover:scale-105 glow-primary uppercase tracking-wide">
                                    Post
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- Feed Posts --}}
                @forelse($feed as $post)
                <div class="card card-post rounded-2xl border border-primary/15 bg-gradient-to-b from-surface-container/80 to-surface-container/40 p-5 shadow-sm hover:shadow-card-lg transition-all duration-300 group glass-premium hover:glow-primary">
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

                            <div class="flex items-center gap-1 mt-4 pt-3 border-t border-primary/10">
                                <form action="{{ route('posts.like', $post) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="focus-ring reaction-button inline-flex items-center gap-1.5 text-xs font-bold px-3 py-2 rounded-lg {{ auth()->user()->hasLiked($post) ? 'text-on-primary bg-gradient-to-r from-primary/80 to-primary/60 glow-primary scale-105' : 'text-on-surface-variant hover:text-primary hover:bg-primary/15 hover:glow-primary hover:scale-105' }} transition-all duration-200">
                                        <svg class="w-4 h-4" fill="{{ auth()->user()->hasLiked($post) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                        {{ $post->likes_count }}
                                    </button>
                                </form>
                                <a href="{{ route('posts.show', $post) }}" class="focus-ring reaction-button inline-flex items-center gap-1.5 text-xs font-bold text-on-surface-variant hover:text-primary px-3 py-2 rounded-lg hover:bg-tertiary/15 transition-all duration-200 hover:glow-primary hover:scale-105">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    {{ $post->comments_count }}
                                </a>
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-on-surface-variant px-3 py-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                                    {{ $post->shares_count }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="rounded-2xl border-2 border-dashed border-primary/30 p-12 text-center glass-premium hover:glow-primary transition-all text-reveal">
                    <div class="text-6xl mb-4 float-gentle">⚽</div>
                    <h3 class="font-bold text-h5 text-on-surface mb-2 uppercase tracking-wide">Your Feed is Ready!</h3>
                    <p class="text-sm text-on-surface-variant mb-6 font-medium">Start by following users or joining communities to see posts from your football community.</p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('communities.index') }}" class="focus-ring btn-primary inline-flex items-center gap-2 rounded-xl text-sm font-bold hover:scale-105 glow-primary uppercase tracking-wide card-hover-lift">
                            📍 Explore Communities
                        </a>
                        <a href="{{ route('search') }}" class="focus-ring btn-secondary inline-flex items-center gap-2 rounded-xl px-6 py-3 text-sm text-primary font-bold uppercase tracking-wide">
                            👥 Find Users
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            {{-- Sidebar --}}
            <div class="space-y-4 md:space-y-5">
                {{-- Achievements Section --}}
                <div class="card rounded-2xl border border-secondary/25 bg-gradient-to-b from-secondary/8 to-secondary/4 p-6 shadow-sm glass-premium hover:glow-primary-sm transition-all card-entrance">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-2xl celebrate">🏆</span>
                        <h3 class="font-bold text-h6 text-on-surface uppercase tracking-wide">Your Streak</h3>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-gradient-to-r from-secondary/15 to-secondary/10 border border-secondary/20 hover:from-secondary/25 hover:to-secondary/15 transition-all smooth-color-transition card-hover-lift">
                            <div class="flex items-center gap-2">
                                <span class="text-xl pulse-energy">🔥</span>
                                <span class="text-on-surface-variant font-semibold text-xs md:text-sm">Current Streak</span>
                            </div>
                            <span class="font-bold text-secondary text-base md:text-lg">7d</span>
                        </div>
                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-primary/10 border border-primary/15 hover:bg-primary/15 transition-all smooth-color-transition card-hover-lift">
                            <span class="text-on-surface-variant font-semibold text-xs md:text-sm">Posts This Week</span>
                            <span class="font-bold text-primary text-base md:text-lg">{{ auth()->user()->posts()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-tertiary/10 border border-tertiary/15 hover:bg-tertiary/15 transition-all smooth-color-transition card-hover-lift">
                            <span class="text-on-surface-variant font-semibold text-xs md:text-sm">Total Posts</span>
                            <span class="font-bold text-tertiary text-base md:text-lg">{{ auth()->user()->posts()->count() }}</span>
                        </div>
                        <a href="{{ route('leaderboard') }}" class="focus-ring inline-flex items-center gap-1 text-primary hover:text-primary/80 font-bold text-xs mt-2 transition-colors hover:underline">
                            View All Badges →
                        </a>
                    </div>
                </div>

                {{-- Fan Streaks Leaderboard --}}
                <div class="card rounded-2xl border border-tertiary/25 bg-gradient-to-b from-surface-container/80 to-surface-container/40 overflow-hidden shadow-sm glass-premium hover:glow-primary transition-all card-entrance">
                    <div class="px-5 py-4 bg-gradient-to-r from-tertiary/15 to-tertiary/8 border-b border-tertiary/20">
                        <h3 class="font-bold text-h6 text-on-surface flex items-center gap-2 uppercase tracking-wide">
                            <span class="text-lg">⚡</span> Top Streaks
                        </h3>
                    </div>
                    <div class="p-2.5 space-y-1">
                        <div class="flex items-center justify-between p-1.5 rounded-lg hover:bg-tertiary/5 transition-all smooth-color-transition card-hover-lift stagger-1">
                            <div class="flex items-center gap-2 min-w-0 flex-1">
                                <span class="font-bold text-tertiary min-w-[20px] text-center text-sm">1</span>
                                <span class="text-xs md:text-sm font-medium text-on-surface truncate">You</span>
                            </div>
                            <span class="font-bold text-tertiary text-xs md:text-sm ml-1 flex-shrink-0">7🔥</span>
                        </div>
                        <div class="flex items-center justify-between p-1.5 rounded-lg hover:bg-tertiary/5 transition-all smooth-color-transition card-hover-lift stagger-2">
                            <div class="flex items-center gap-2 min-w-0 flex-1">
                                <span class="font-bold text-on-surface-variant min-w-[20px] text-center text-sm">2</span>
                                <span class="text-xs md:text-sm font-medium text-on-surface-variant truncate">Marcus</span>
                            </div>
                            <span class="font-bold text-on-surface-variant text-xs md:text-sm ml-1 flex-shrink-0">12🔥</span>
                        </div>
                        <div class="flex items-center justify-between p-1.5 rounded-lg hover:bg-tertiary/5 transition-all smooth-color-transition card-hover-lift stagger-3">
                            <div class="flex items-center gap-2 min-w-0 flex-1">
                                <span class="font-bold text-on-surface-variant min-w-[20px] text-center text-sm">3</span>
                                <span class="text-xs md:text-sm font-medium text-on-surface-variant truncate">Sarah</span>
                            </div>
                            <span class="font-bold text-on-surface-variant text-xs md:text-sm ml-1 flex-shrink-0">5🔥</span>
                        </div>
                        <a href="{{ route('leaderboard') }}" class="focus-ring inline-flex items-center gap-1 text-tertiary hover:text-tertiary/80 font-bold text-xs mt-2 transition-colors hover:underline">
                            Full Leaderboard →
                        </a>
                    </div>
                </div>

                {{-- Active Polls --}}
                <div class="card rounded-2xl border border-tertiary/25 bg-gradient-to-b from-surface-container/80 to-surface-container/40 overflow-hidden shadow-sm glass-premium hover:glow-primary transition-all">
                    <div class="px-5 py-4 bg-gradient-to-r from-tertiary/15 to-tertiary/8 border-b border-tertiary/20">
                        <h3 class="font-bold text-h6 text-on-surface flex items-center gap-2 uppercase tracking-wide">
                            <span class="text-lg">📊</span> Active Polls
                        </h3>
                    </div>
                    <div class="p-2.5 space-y-1.5">
                        @forelse($activePolls as $poll)
                        <a href="{{ route('polls.show', $poll) }}" class="block p-2 rounded-xl hover:bg-tertiary/5 dark:hover:bg-tertiary/10 transition-all duration-200">
                            <div class="text-xs md:text-sm font-medium text-on-surface line-clamp-1">{{ $poll->title }}</div>
                            <div class="flex justify-between text-xs text-on-surface-variant mt-1">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                    {{ $poll->total_votes }}
                                </span>
                                @if($poll->closes_at)
                                <span class="truncate">{{ $poll->closes_at->diffForHumans() }}</span>
                                @endif
                            </div>
                        </a>
                        @empty
                        <p class="text-xs md:text-sm text-on-surface-variant p-2 text-center">No polls.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Sidebar Ad --}}
                <x-ad-unit placement="sidebar" />

                {{-- Trending Posts --}}
                <div class="card rounded-2xl border border-secondary/25 bg-gradient-to-b from-surface-container/80 to-surface-container/40 overflow-hidden shadow-sm glass-premium hover:glow-primary transition-all">
                    <div class="px-5 py-4 bg-gradient-to-r from-secondary/15 to-secondary/8 border-b border-secondary/20">
                        <h3 class="font-bold text-h6 text-on-surface flex items-center gap-2 uppercase tracking-wide">
                            <span class="text-lg">🔥</span> Trending
                        </h3>
                    </div>
                    <div class="p-2.5 space-y-1.5">
                        @forelse($trendingPosts as $post)
                        <a href="{{ route('posts.show', $post) }}" class="block p-2 rounded-xl hover:bg-secondary/5 dark:hover:bg-secondary/10 transition-all duration-200">
                            <div class="text-xs md:text-sm text-on-surface line-clamp-2 leading-snug">{{ Str::limit($post->body, 60) }}</div>
                            <div class="flex items-center gap-1.5 mt-1.5 text-xs text-on-surface-variant">
                                <span class="font-medium truncate">{{ Str::limit($post->user->name, 12) }}</span>
                                <span class="text-outline-variant/40">·</span>
                                <span class="inline-flex items-center gap-0.5 text-primary flex-shrink-0">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    {{ $post->likes_count }}
                                </span>
                            </div>
                        </a>
                        @empty
                        <p class="text-xs md:text-sm text-on-surface-variant p-2 text-center">Nothing trending.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Mini League Table --}}
                <div class="card rounded-2xl border border-primary/25 bg-gradient-to-b from-primary/8 to-primary/4 overflow-hidden shadow-sm glass-premium hover:glow-primary-sm transition-all card-entrance">
                    <div class="px-5 py-4 bg-gradient-to-r from-primary/15 to-primary/8 border-b border-primary/20">
                        <h3 class="font-bold text-h6 text-on-surface flex items-center gap-2 uppercase tracking-wide">
                            <span class="text-lg">⚽</span> Points Race
                        </h3>
                    </div>
                    <div class="p-2.5 space-y-1">
                        <div class="flex items-center justify-between p-1.5 rounded-lg bg-gradient-to-r from-primary/10 to-primary/5 border border-primary/20 smooth-color-transition card-hover-lift achievement-unlock">
                            <div class="flex items-center gap-2 flex-1 min-w-0">
                                <span class="font-bold text-base md:text-lg">🥇</span>
                                <span class="text-xs md:text-sm font-medium text-on-surface truncate">You</span>
                            </div>
                            <div class="flex items-center gap-1 ml-1 flex-shrink-0">
                                <span class="font-bold text-primary text-xs md:text-sm">{{ auth()->user()->points ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-1.5 rounded-lg hover:bg-primary/5 transition-all smooth-color-transition card-hover-lift stagger-1">
                            <div class="flex items-center gap-2 flex-1 min-w-0">
                                <span class="font-bold text-base md:text-lg">🥈</span>
                                <span class="text-xs md:text-sm font-medium text-on-surface-variant truncate">James</span>
                            </div>
                            <div class="flex items-center gap-1 ml-1 flex-shrink-0">
                                <span class="font-bold text-on-surface-variant text-xs md:text-sm">1,240</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-1.5 rounded-lg hover:bg-primary/5 transition-all smooth-color-transition card-hover-lift stagger-2">
                            <div class="flex items-center gap-2 flex-1 min-w-0">
                                <span class="font-bold text-base md:text-lg">🥉</span>
                                <span class="text-xs md:text-sm font-medium text-on-surface-variant truncate">Alex</span>
                            </div>
                            <div class="flex items-center gap-1 ml-1 flex-shrink-0">
                                <span class="font-bold text-on-surface-variant text-xs md:text-sm">980</span>
                            </div>
                        </div>
                        <a href="{{ route('leaderboard') }}" class="focus-ring inline-flex items-center gap-1 text-primary hover:text-primary/80 font-bold text-xs mt-2 transition-colors hover:underline">
                            Full Rankings →
                        </a>
                    </div>
                </div>

                {{-- Your Communities --}}
                @php $userCommunities = auth()->user()->communities()->limit(5)->get(); @endphp
                @if($userCommunities->isNotEmpty())
                <div class="rounded-2xl border border-primary/20 bg-gradient-to-b from-surface-container/80 to-surface-container/40 overflow-hidden shadow-sm glass-premium hover:glow-primary-sm transition-all">
                    <div class="px-5 py-4 bg-gradient-to-r from-primary/15 to-primary/8 border-b border-primary/20">
                        <h3 class="font-bold text-h6 text-on-surface flex items-center gap-2 uppercase tracking-wide">
                            <span class="text-lg">👥</span> Your Communities
                        </h3>
                    </div>
                    <div class="p-2.5 space-y-1.5">
                        @foreach($userCommunities as $community)
                        <a href="{{ route('communities.show', $community) }}" class="block p-1.5 rounded-lg hover:bg-primary/10 dark:hover:bg-primary/15 transition-all duration-200">
                            <div class="text-xs md:text-sm font-medium text-on-surface truncate">{{ $community->name }}</div>
                            <div class="text-xs text-on-surface-variant">{{ $community->members_count ?? 0 }} members</div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts::app>
