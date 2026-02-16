<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        {{-- Live Matches Banner --}}
        @if($liveMatches->isNotEmpty())
        <div class="rounded-xl bg-red-50 p-4 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
            <div class="flex items-center gap-2 mb-3">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                </span>
                <h2 class="text-lg font-bold text-red-700 dark:text-red-400">Live Matches</h2>
            </div>
            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                @foreach($liveMatches as $match)
                <a href="{{ route('matches.show', $match->id) }}" class="block rounded-lg bg-white dark:bg-zinc-800 p-3 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center gap-1 text-xs text-zinc-500 dark:text-zinc-400 mb-1">
                        @if($match->league['logo'] ?? null)
                        <img src="{{ $match->league['logo'] }}" alt="" class="h-3 w-3 object-contain">
                        @endif
                        {{ $match->league['name'] }}
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            @if($match->home_team['logo'] ?? null)
                            <img src="{{ $match->home_team['logo'] }}" alt="" class="h-5 w-5 object-contain">
                            @endif
                            <span class="font-semibold text-sm">{{ $match->home_team['name'] }}</span>
                        </div>
                        <span class="font-bold text-lg text-green-600 dark:text-green-400">{{ $match->score_display }}</span>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-sm">{{ $match->away_team['name'] }}</span>
                            @if($match->away_team['logo'] ?? null)
                            <img src="{{ $match->away_team['logo'] }}" alt="" class="h-5 w-5 object-contain">
                            @endif
                        </div>
                    </div>
                    <div class="text-center text-xs text-red-500 mt-1">
                        {{ ucfirst(str_replace('_', ' ', $match->status)) }}
                        @if($match->elapsed) · {{ $match->elapsed }}' @endif
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-3">
            {{-- Main Feed --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Your Feed</h2>
                    <a href="{{ route('posts.index') }}" class="text-sm text-green-600 hover:text-green-700 dark:text-green-400">View All →</a>
                </div>

                {{-- Create Post --}}
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                    @csrf
                    <textarea name="body" rows="3" placeholder="What's on your mind about football?" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white focus:border-green-500 focus:ring-green-500 text-sm"></textarea>
                    <div class="flex items-center justify-between mt-2">
                        <input type="file" name="media[]" multiple accept="image/*,video/*" class="text-xs text-zinc-500">
                        <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 transition">Post</button>
                    </div>
                </form>

                {{-- Feed Posts --}}
                @forelse($feed as $post)
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center text-green-700 dark:text-green-300 font-bold text-sm">
                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('profiles.show', $post->user) }}" class="font-semibold text-sm text-zinc-900 dark:text-white hover:text-green-600">{{ $post->user->name }}</a>
                                @if($post->community)
                                <span class="text-xs text-zinc-400">in</span>
                                <a href="{{ route('communities.show', $post->community) }}" class="text-xs text-green-600 hover:text-green-700">{{ $post->community->name }}</a>
                                @endif
                                <span class="text-xs text-zinc-400">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="mt-1 text-sm text-zinc-700 dark:text-zinc-300">{{ $post->body }}</p>

                            @if($post->media)
                            <div class="mt-2 grid gap-2 {{ count($post->media) > 1 ? 'grid-cols-2' : '' }}">
                                @foreach($post->media as $media)
                                <img src="{{ asset('storage/' . $media) }}" alt="" class="rounded-lg max-h-64 w-full object-cover">
                                @endforeach
                            </div>
                            @endif

                            <div class="flex items-center gap-4 mt-3">
                                <form action="{{ route('posts.like', $post) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-1 text-xs {{ auth()->user()->hasLiked($post) ? 'text-green-600' : 'text-zinc-400 hover:text-green-600' }} transition">
                                        ♥ {{ $post->likes_count }}
                                    </button>
                                </form>
                                <a href="{{ route('posts.show', $post) }}" class="flex items-center gap-1 text-xs text-zinc-400 hover:text-green-600 transition">
                                    💬 {{ $post->comments_count }}
                                </a>
                                <span class="text-xs text-zinc-400">🔗 {{ $post->shares_count }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-8 text-center">
                    <p class="text-zinc-500 dark:text-zinc-400">No posts yet. Follow some users or join communities to build your feed!</p>
                </div>
                @endforelse
            </div>

            {{-- Sidebar --}}
            <div class="space-y-4">
                {{-- Upcoming Matches --}}
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                    <h3 class="font-bold text-sm text-zinc-900 dark:text-white mb-3">Upcoming Matches</h3>
                    @forelse($upcomingMatches as $match)
                    <a href="{{ route('matches.show', $match->id) }}" class="block py-2 border-b border-zinc-100 dark:border-zinc-700 last:border-0 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 -mx-2 px-2 rounded transition">
                        <div class="flex items-center gap-1 text-xs text-zinc-400">
                            @if($match->league['logo'] ?? null)
                            <img src="{{ $match->league['logo'] }}" alt="" class="h-3 w-3 object-contain">
                            @endif
                            {{ \Carbon\Carbon::parse($match->date)->format('M d, H:i') }} · {{ $match->league['name'] }}
                        </div>
                        <div class="flex justify-between items-center mt-1 text-sm font-medium text-zinc-800 dark:text-zinc-200">
                            <div class="flex items-center gap-1">
                                @if($match->home_team['logo'] ?? null)
                                <img src="{{ $match->home_team['logo'] }}" alt="" class="h-4 w-4 object-contain">
                                @endif
                                <span>{{ $match->home_team['name'] }}</span>
                            </div>
                            <span class="text-xs text-zinc-400">vs</span>
                            <div class="flex items-center gap-1">
                                <span>{{ $match->away_team['name'] }}</span>
                                @if($match->away_team['logo'] ?? null)
                                <img src="{{ $match->away_team['logo'] }}" alt="" class="h-4 w-4 object-contain">
                                @endif
                            </div>
                        </div>
                    </a>
                    @empty
                    <p class="text-sm text-zinc-400">No upcoming matches.</p>
                    @endforelse
                </div>

                {{-- Active Polls --}}
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                    <h3 class="font-bold text-sm text-zinc-900 dark:text-white mb-3">Active Polls</h3>
                    @forelse($activePolls as $poll)
                    <a href="{{ route('polls.show', $poll) }}" class="block py-2 border-b border-zinc-100 dark:border-zinc-700 last:border-0 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 -mx-2 px-2 rounded transition">
                        <div class="text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $poll->title }}</div>
                        <div class="flex justify-between text-xs text-zinc-400 mt-1">
                            <span>{{ $poll->total_votes }} votes</span>
                            @if($poll->closes_at)
                            <span>Closes {{ $poll->closes_at->diffForHumans() }}</span>
                            @endif
                        </div>
                    </a>
                    @empty
                    <p class="text-sm text-zinc-400">No active polls.</p>
                    @endforelse
                </div>

                {{-- Trending Posts --}}
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                    <h3 class="font-bold text-sm text-zinc-900 dark:text-white mb-3">🔥 Trending</h3>
                    @forelse($trendingPosts as $post)
                    <a href="{{ route('posts.show', $post) }}" class="block py-2 border-b border-zinc-100 dark:border-zinc-700 last:border-0 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 -mx-2 px-2 rounded transition">
                        <div class="text-sm text-zinc-700 dark:text-zinc-300 line-clamp-2">{{ Str::limit($post->body, 80) }}</div>
                        <div class="text-xs text-zinc-400 mt-1">{{ $post->user->name }} · ♥ {{ $post->likes_count }}</div>
                    </a>
                    @empty
                    <p class="text-sm text-zinc-400">Nothing trending yet.</p>
                    @endforelse
                </div>

                {{-- Favorite Clubs --}}
                @if($favoriteClubs->isNotEmpty())
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                    <h3 class="font-bold text-sm text-zinc-900 dark:text-white mb-3">Your Clubs</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($favoriteClubs as $club)
                        <a href="{{ route('clubs.show', $club) }}" class="inline-flex items-center gap-1 rounded-full bg-green-50 dark:bg-green-900/30 px-3 py-1 text-xs font-medium text-green-700 dark:text-green-400 hover:bg-green-100 transition">
                            {{ $club->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts::app>
