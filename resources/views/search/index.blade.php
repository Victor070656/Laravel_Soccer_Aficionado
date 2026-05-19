<x-layouts::app :title="__('Search')">
    <div class="max-w-4xl mx-auto space-y-8 p-2 sm:p-4">
        {{-- Header --}}
        <div class="relative rounded-2xl overflow-hidden bg-gradient-stadium-primary p-6 sm:p-8 text-on-primary shadow-xl shadow-primary/20 glow-primary-lg">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/3 -translate-x-1/4"></div>
            <div class="relative z-10">
                <h1 class="text-2xl sm:text-3xl font-bold mb-4 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl">🔍</span>
                    Search
                </h1>
                <form action="{{ route('search') }}" method="GET">
                    <div class="relative">
                        <input type="text" name="q" value="{{ $query ?? '' }}" placeholder="Search users, clubs, communities, posts, matches..."
                            class="focus-ring w-full rounded-xl border border-primary/25 bg-surface/50 text-on-surface placeholder:text-on-surface-variant/70 pl-12 py-3.5 text-base focus:border-primary focus:ring-2 focus:ring-primary/25 transition" autofocus>
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/60 text-lg">🔍</span>
                    </div>
                </form>
            </div>
        </div>

        @isset($query)
        {{-- Users --}}
        @if($users->count())
        <div>
            <h2 class="font-bold text-on-surface mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 text-sm">👤</span>
                Users
                <span class="text-xs font-medium text-on-surface-variant ml-1">({{ $users->count() }})</span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($users as $user)
                <a href="{{ route('profiles.show', $user) }}" class="flex items-center gap-3 rounded-2xl border border-outline-variant/20 bg-surface-container/80 p-4 shadow-sm hover:shadow-card hover:border-primary/40 transition-all group glass-premium">
                    @if($user->avatar)
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-11 h-11 rounded-full object-cover shadow-sm group-hover:scale-110 transition-transform">
                    @else
                    <div class="w-11 h-11 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-bold shadow-sm group-hover:scale-110 transition-transform">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    @endif
                    <div>
                        <div class="font-semibold text-on-surface group-hover:text-primary transition">{{ $user->name }}</div>
                        @if($user->username)
                        <div class="text-xs text-on-surface-variant">{{ '@' . $user->username }}</div>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Clubs --}}
        @if($clubs->count())
        <div>
            <h2 class="font-bold text-on-surface mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 text-sm">⚽</span>
                Clubs
                <span class="text-xs font-medium text-on-surface-variant ml-1">({{ $clubs->count() }})</span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($clubs as $club)
                <a href="{{ route('clubs.show', $club->id) }}" class="flex items-center gap-3 rounded-2xl border border-outline-variant/20 bg-surface-container/80 p-4 shadow-sm hover:shadow-card hover:border-primary/40 transition-all group glass-premium">
                    @if($club->logo)
                    <div class="w-11 h-11 rounded-xl bg-zinc-50 dark:bg-zinc-700/50 flex items-center justify-center p-1 group-hover:scale-110 transition-transform">
                        <img loading="lazy" decoding="async" src="{{ $club->logo }}" alt="" class="w-8 h-8 object-contain">
                    </div>
                    @else
                    <div class="w-11 h-11 rounded-xl bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center group-hover:scale-110 transition-transform">⚽</div>
                    @endif
                    <div>
                        <div class="font-semibold text-on-surface group-hover:text-primary transition">{{ $club->name }}</div>
                        <div class="text-xs text-on-surface-variant">{{ $club->country }}</div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Communities --}}
        @if($communities->count())
        <div>
            <h2 class="font-bold text-on-surface mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 text-sm">👥</span>
                Communities
                <span class="text-xs font-medium text-on-surface-variant ml-1">({{ $communities->count() }})</span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($communities as $community)
                <a href="{{ route('communities.show', $community) }}" class="flex items-center gap-3 rounded-2xl border border-outline-variant/20 bg-surface-container/80 p-4 shadow-sm hover:shadow-card hover:border-primary/40 transition-all group glass-premium">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-purple-100 to-violet-100 dark:from-purple-900/30 dark:to-violet-900/30 flex items-center justify-center text-lg group-hover:scale-110 transition-transform">👥</div>
                    <div>
                        <div class="font-semibold text-on-surface group-hover:text-primary transition">{{ $community->name }}</div>
                        <div class="text-xs text-on-surface-variant">{{ $community->members_count }} members</div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Posts --}}
        @if($posts->count())
        <div>
            <h2 class="font-bold text-on-surface mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 text-sm">📰</span>
                Posts
                <span class="text-xs font-medium text-on-surface-variant ml-1">({{ $posts->count() }})</span>
            </h2>
            <div class="space-y-3">
                @foreach($posts as $post)
                <a href="{{ route('posts.show', $post) }}" class="block rounded-2xl border border-outline-variant/20 bg-surface-container/80 p-5 shadow-sm hover:shadow-card hover:border-primary/40 transition-all glass-premium">
                    <div class="flex items-center gap-2 mb-2">
                        @if($post->user->avatar)
                        <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}" class="w-6 h-6 rounded-full object-cover">
                        @else
                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white text-[10px] font-bold">{{ strtoupper(substr($post->user->name, 0, 1)) }}</div>
                        @endif
                        <span class="text-sm font-medium text-on-surface-variant">{{ $post->user->name }}</span>
                        <span class="text-xs text-on-surface-variant">{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-on-surface leading-relaxed">{{ Str::limit($post->body, 150) }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Matches --}}
        @if($matches->count())
        <div>
            <h2 class="font-bold text-on-surface mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-red-600 text-sm">⚽</span>
                Matches
                <span class="text-xs font-medium text-on-surface-variant ml-1">({{ $matches->count() }})</span>
            </h2>
            <div class="space-y-3">
                @foreach($matches as $match)
                <a href="{{ route('matches.show', $match) }}" class="flex items-center justify-between rounded-2xl border border-outline-variant/20 bg-surface-container/80 p-4 shadow-sm hover:shadow-card hover:border-primary/40 transition-all group glass-premium">
                    <span class="font-semibold text-on-surface group-hover:text-primary transition">{{ $match->homeClub->name }} vs {{ $match->awayClub->name }}</span>
                    <span class="text-xs text-on-surface-variant flex-shrink-0 ml-3">{{ $match->kick_off->format('M d, Y') }}</span>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- No Results --}}
        @if(!$users->count() && !$clubs->count() && !$communities->count() && !$posts->count() && !$matches->count())
        <div class="rounded-2xl border-2 border-dashed border-outline-variant/30 p-12 text-center glass-premium">
            <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-surface-container-high flex items-center justify-center">
                <span class="text-4xl">🔍</span>
            </div>
            <h2 class="text-xl font-bold text-on-surface mb-2">No Results Found</h2>
            <p class="text-sm text-on-surface-variant max-w-md mx-auto">No results found for "<strong class="text-on-surface">{{ $query }}</strong>". Try a different search term.</p>
        </div>
        @endif
        @endisset
    </div>
</x-layouts::app>
