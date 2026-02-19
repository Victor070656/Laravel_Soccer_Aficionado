<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-8 rounded-xl p-2 sm:p-4">

        {{-- Welcome Banner --}}
        <div class="relative rounded-2xl overflow-hidden bg-gradient-to-r from-green-600 via-emerald-600 to-green-500 p-6 sm:p-8 text-white shadow-xl shadow-green-600/20">
            <div class="absolute inset-0 opacity-10" style="background-image: url('https://images.unsplash.com/photo-1508098682722-e99c643e7f0b?w=600&q=30'); background-size: cover; background-position: center;"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/4"></div>
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}! 👋</h1>
                    <p class="text-green-100 text-sm sm:text-base">Stay updated with the latest matches, posts, and community activity.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="glass rounded-xl px-4 py-3 text-center min-w-[80px]">
                        <div class="text-2xl font-bold">{{ auth()->user()->points ?? 0 }}</div>
                        <div class="text-xs text-green-100">Points</div>
                    </div>
                    <div class="glass rounded-xl px-4 py-3 text-center min-w-[80px]">
                        <div class="text-2xl font-bold">{{ auth()->user()->badges()->count() }}</div>
                        <div class="text-xs text-green-100">Badges</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Live Matches Banner --}}
        @if($liveMatches->isNotEmpty())
        <div class="rounded-2xl bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/20 p-5 sm:p-6 border border-red-200/60 dark:border-red-800/40 shadow-lg shadow-red-500/5">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex items-center gap-2 bg-red-500 text-white px-3 py-1.5 rounded-full text-sm font-bold shadow-lg shadow-red-500/30">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                    </span>
                    LIVE
                </div>
                <h2 class="text-lg font-bold text-red-700 dark:text-red-400">Live Matches</h2>
                <a href="{{ route('matches.live') }}" class="ml-auto text-sm text-red-600 hover:text-red-700 dark:text-red-400 font-medium">View All →</a>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($liveMatches as $match)
                <a href="{{ route('matches.show', $match->id) }}" class="group block rounded-xl bg-white dark:bg-zinc-800 p-4 shadow-md hover:shadow-xl transition-all duration-300 border border-zinc-100 dark:border-zinc-700/50 hover:-translate-y-1">
                    <div class="flex items-center gap-1.5 text-xs text-zinc-500 dark:text-zinc-400 mb-2">
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
                        <div class="flex-shrink-0 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-bold text-lg px-3 py-1 rounded-lg shadow-sm">{{ $match->score_display }}</div>
                        <div class="flex items-center gap-2 flex-1 min-w-0 justify-end">
                            <span class="font-semibold text-sm truncate">{{ $match->away_team['name'] }}</span>
                            @if($match->away_team['logo'] ?? null)
                            <img loading="lazy" decoding="async" src="{{ $match->away_team['logo'] }}" alt="" class="h-6 w-6 object-contain flex-shrink-0">
                            @endif
                        </div>
                    </div>
                    <div class="text-center mt-2">
                        <span class="inline-flex items-center gap-1.5 text-xs text-red-500 font-medium bg-red-50 dark:bg-red-900/30 px-2 py-0.5 rounded-full">
                            <span class="relative flex h-1.5 w-1.5"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span><span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-red-500"></span></span>
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
                    <h2 class="text-xl font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 text-sm">📰</span>
                        Your Feed
                    </h2>
                    <a href="{{ route('posts.index') }}" class="text-sm text-green-600 hover:text-green-700 dark:text-green-400 font-medium hover:underline">View All →</a>
                </div>

                {{-- Create Post --}}
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 p-5 shadow-sm hover:shadow-md transition-shadow">
                    @csrf
                    <div class="flex items-start gap-3">
                        @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="flex-shrink-0 w-10 h-10 rounded-full object-cover shadow-md">
                        @else
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-bold text-sm shadow-md">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        @endif
                        <div class="flex-1">
                            <textarea name="body" rows="3" placeholder="What's on your mind about football? ⚽" class="w-full rounded-xl p-4 border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white focus:border-green-500 focus:ring-green-500/20 text-sm resize-none placeholder:text-zinc-400"></textarea>
                            <div class="flex items-center justify-between mt-3">
                                <label class="inline-flex items-center gap-2 text-xs text-zinc-500 hover:text-green-600 cursor-pointer transition-colors px-3 py-2 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Add Media
                                    <input type="file" name="media[]" multiple accept="image/*,video/*" class="hidden">
                                </label>
                                <button type="submit" class="rounded-xl bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-2 text-sm font-semibold text-white hover:from-green-500 hover:to-emerald-400 transition-all duration-300 shadow-md shadow-green-600/20 hover:shadow-lg hover:shadow-green-500/30 hover:scale-105">
                                    Post
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- Feed Posts --}}
                @forelse($feed as $post)
                <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 p-5 shadow-sm hover:shadow-md transition-all duration-300 group">
                    <div class="flex items-start gap-3">
                        <a href="{{ route('profiles.show', $post->user) }}" class="flex-shrink-0 w-11 h-11 rounded-full hover:scale-110 transition-transform">
                            @if($post->user->avatar)
                            <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}" class="w-11 h-11 rounded-full object-cover shadow-md">
                            @else
                            <div class="w-11 h-11 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-bold text-sm shadow-md">
                                {{ strtoupper(substr($post->user->name, 0, 1)) }}
                            </div>
                            @endif
                        </a>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <a href="{{ route('profiles.show', $post->user) }}" class="font-bold text-sm text-zinc-900 dark:text-white hover:text-green-600 dark:hover:text-green-400 transition-colors">{{ $post->user->name }}</a>
                                @if($post->community)
                                <span class="text-xs text-zinc-400">in</span>
                                <a href="{{ route('communities.show', $post->community) }}" class="inline-flex items-center gap-1 text-xs font-medium text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/30 px-2 py-0.5 rounded-full hover:bg-green-100 dark:hover:bg-green-900/50 transition-colors">{{ $post->community->name }}</a>
                                @endif
                                <span class="text-xs text-zinc-400">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="mt-2 text-sm text-zinc-700 dark:text-zinc-300 leading-relaxed">{{ $post->body }}</p>

                            @if($post->media)
                            <div class="mt-3 grid gap-2 {{ count($post->media) > 1 ? 'grid-cols-2' : '' }} rounded-xl overflow-hidden">
                                @foreach($post->media as $media)
                                <img loading="lazy" decoding="async" src="{{ asset('storage/' . $media) }}" alt="" class="rounded-xl max-h-72 w-full object-cover hover:opacity-90 transition-opacity">
                                @endforeach
                            </div>
                            @endif

                            <div class="flex items-center gap-1 mt-4 pt-3 border-t border-zinc-100 dark:border-zinc-700/50">
                                <form action="{{ route('posts.like', $post) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-2 rounded-lg {{ auth()->user()->hasLiked($post) ? 'text-green-600 bg-green-50 dark:bg-green-900/30' : 'text-zinc-500 hover:text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20' }} transition-all">
                                        <svg class="w-4 h-4" fill="{{ auth()->user()->hasLiked($post) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                        {{ $post->likes_count }}
                                    </button>
                                </form>
                                <a href="{{ route('posts.show', $post) }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-zinc-500 hover:text-green-600 px-3 py-2 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    {{ $post->comments_count }}
                                </a>
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-zinc-400 px-3 py-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                                    {{ $post->shares_count }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="rounded-2xl border-2 border-dashed border-zinc-200 dark:border-zinc-700 p-12 text-center">
                    <div class="text-5xl mb-4">⚽</div>
                    <h3 class="font-bold text-zinc-900 dark:text-white mb-2">Your feed is empty</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">Follow some users or join communities to build your feed!</p>
                    <a href="{{ route('communities.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-2.5 text-sm text-white font-semibold hover:from-green-500 hover:to-emerald-400 transition-all shadow-md">
                        Explore Communities
                    </a>
                </div>
                @endforelse
            </div>

            {{-- Sidebar --}}
            <div class="space-y-5">
                {{-- Upcoming Matches --}}
                <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 overflow-hidden shadow-sm">
                    <div class="px-5 py-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-b border-zinc-100 dark:border-zinc-700/50">
                        <h3 class="font-bold text-sm text-zinc-900 dark:text-white flex items-center gap-2">
                            <span class="text-base">📅</span> Upcoming Matches
                        </h3>
                    </div>
                    <div class="p-3">
                        @forelse($upcomingMatches as $match)
                        <a href="{{ route('matches.show', $match->id) }}" class="block p-3 rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-all duration-200 group/match">
                            <div class="flex items-center gap-1.5 text-xs text-zinc-400 mb-1.5">
                                @if($match->league['logo'] ?? null)
                                <img loading="lazy" decoding="async" src="{{ $match->league['logo'] }}" alt="" class="h-3.5 w-3.5 object-contain">
                                @endif
                                <span>{{ \Carbon\Carbon::parse($match->date)->format('M d, H:i') }}</span>
                                <span class="text-zinc-300 dark:text-zinc-600">·</span>
                                <span>{{ $match->league['name'] }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm font-medium text-zinc-800 dark:text-zinc-200">
                                <div class="flex items-center gap-1.5">
                                    @if($match->home_team['logo'] ?? null)
                                    <img loading="lazy" decoding="async" src="{{ $match->home_team['logo'] }}" alt="" class="h-5 w-5 object-contain">
                                    @endif
                                    <span class="group-hover/match:text-green-600 dark:group-hover/match:text-green-400 transition-colors">{{ $match->home_team['name'] }}</span>
                                </div>
                                <span class="text-xs font-normal text-zinc-400 bg-zinc-100 dark:bg-zinc-700 px-2 py-0.5 rounded-md">vs</span>
                                <div class="flex items-center gap-1.5">
                                    <span class="group-hover/match:text-green-600 dark:group-hover/match:text-green-400 transition-colors">{{ $match->away_team['name'] }}</span>
                                    @if($match->away_team['logo'] ?? null)
                                    <img loading="lazy" decoding="async" src="{{ $match->away_team['logo'] }}" alt="" class="h-5 w-5 object-contain">
                                    @endif
                                </div>
                            </div>
                        </a>
                        @empty
                        <p class="text-sm text-zinc-400 p-3 text-center">No upcoming matches.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Active Polls --}}
                <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 overflow-hidden shadow-sm">
                    <div class="px-5 py-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 border-b border-zinc-100 dark:border-zinc-700/50">
                        <h3 class="font-bold text-sm text-zinc-900 dark:text-white flex items-center gap-2">
                            <span class="text-base">📊</span> Active Polls
                        </h3>
                    </div>
                    <div class="p-3">
                        @forelse($activePolls as $poll)
                        <a href="{{ route('polls.show', $poll) }}" class="block p-3 rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-all duration-200">
                            <div class="text-sm font-medium text-zinc-800 dark:text-zinc-200 mb-1.5">{{ $poll->title }}</div>
                            <div class="flex justify-between text-xs text-zinc-400">
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
                        <p class="text-sm text-zinc-400 p-3 text-center">No active polls.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Sidebar Ad --}}
                <x-ad-unit placement="sidebar" />

                {{-- Trending Posts --}}
                <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 overflow-hidden shadow-sm">
                    <div class="px-5 py-4 bg-gradient-to-r from-orange-50 to-amber-50 dark:from-orange-900/20 dark:to-amber-900/20 border-b border-zinc-100 dark:border-zinc-700/50">
                        <h3 class="font-bold text-sm text-zinc-900 dark:text-white flex items-center gap-2">
                            <span class="text-base">🔥</span> Trending
                        </h3>
                    </div>
                    <div class="p-3">
                        @forelse($trendingPosts as $post)
                        <a href="{{ route('posts.show', $post) }}" class="block p-3 rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-all duration-200">
                            <div class="text-sm text-zinc-700 dark:text-zinc-300 line-clamp-2 leading-relaxed">{{ Str::limit($post->body, 80) }}</div>
                            <div class="flex items-center gap-2 mt-2 text-xs text-zinc-400">
                                <span class="font-medium">{{ $post->user->name }}</span>
                                <span class="text-zinc-300 dark:text-zinc-600">·</span>
                                <span class="inline-flex items-center gap-1 text-green-600">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    {{ $post->likes_count }}
                                </span>
                            </div>
                        </a>
                        @empty
                        <p class="text-sm text-zinc-400 p-3 text-center">Nothing trending yet.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Favorite Clubs --}}
                @if($favoriteClubs->isNotEmpty())
                <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 overflow-hidden shadow-sm">
                    <div class="px-5 py-4 bg-gradient-to-r from-green-50 to-teal-50 dark:from-green-900/20 dark:to-teal-900/20 border-b border-zinc-100 dark:border-zinc-700/50">
                        <h3 class="font-bold text-sm text-zinc-900 dark:text-white flex items-center gap-2">
                            <span class="text-base">💚</span> Your Clubs
                        </h3>
                    </div>
                    <div class="p-4">
                        <div class="flex flex-wrap gap-2">
                            @foreach($favoriteClubs as $club)
                            <a href="{{ $club->api_team_id ? route('clubs.show', $club->api_team_id) : '#' }}" class="inline-flex items-center gap-1.5 rounded-full bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30 px-4 py-2 text-xs font-medium text-green-700 dark:text-green-400 hover:from-green-100 hover:to-emerald-100 dark:hover:from-green-900/50 dark:hover:to-emerald-900/50 transition-all border border-green-200/50 dark:border-green-800/50 hover:scale-105">
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
