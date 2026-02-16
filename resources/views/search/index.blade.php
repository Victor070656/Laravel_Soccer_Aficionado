<x-layouts::app :title="__('Search')">
    <div class="max-w-4xl mx-auto space-y-6">
        <form action="{{ route('search') }}" method="GET">
            <div class="relative">
                <input type="text" name="q" value="{{ $query ?? '' }}" placeholder="Search users, clubs, communities, posts, matches..."
                    class="w-full rounded-xl border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white pl-12 py-3 text-lg" autofocus>
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400 text-lg">🔍</span>
            </div>
        </form>

        @isset($query)
        {{-- Users --}}
        @if($users->count())
        <div>
            <h2 class="font-bold text-zinc-900 dark:text-white mb-3">Users</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($users as $user)
                <a href="{{ route('profiles.show', $user) }}" class="flex items-center gap-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4 hover:border-green-400 transition">
                    <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center text-green-700 dark:text-green-300 font-bold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-semibold text-zinc-900 dark:text-white">{{ $user->name }}</div>
                        @if($user->username)
                        <div class="text-xs text-zinc-400">{{ '@' . $user->username }}</div>
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
            <h2 class="font-bold text-zinc-900 dark:text-white mb-3">Clubs</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($clubs as $club)
                <a href="{{ route('clubs.show', $club) }}" class="flex items-center gap-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4 hover:border-green-400 transition">
                    @if($club->logo)
                    <img src="{{ asset('storage/' . $club->logo) }}" alt="" class="w-10 h-10 rounded">
                    @else
                    <div class="w-10 h-10 rounded bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center">⚽</div>
                    @endif
                    <div>
                        <div class="font-semibold text-zinc-900 dark:text-white">{{ $club->name }}</div>
                        <div class="text-xs text-zinc-400">{{ $club->country }}</div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Communities --}}
        @if($communities->count())
        <div>
            <h2 class="font-bold text-zinc-900 dark:text-white mb-3">Communities</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($communities as $community)
                <a href="{{ route('communities.show', $community) }}" class="flex items-center gap-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4 hover:border-green-400 transition">
                    <div class="w-10 h-10 rounded bg-green-100 dark:bg-green-900 flex items-center justify-center">👥</div>
                    <div>
                        <div class="font-semibold text-zinc-900 dark:text-white">{{ $community->name }}</div>
                        <div class="text-xs text-zinc-400">{{ $community->members_count }} members</div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Posts --}}
        @if($posts->count())
        <div>
            <h2 class="font-bold text-zinc-900 dark:text-white mb-3">Posts</h2>
            <div class="space-y-3">
                @foreach($posts as $post)
                <a href="{{ route('posts.show', $post) }}" class="block rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4 hover:border-green-400 transition">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-sm font-medium text-zinc-500">{{ $post->user->name }}</span>
                        <span class="text-xs text-zinc-400">{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-zinc-700 dark:text-zinc-300">{{ Str::limit($post->body, 150) }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Matches --}}
        @if($matches->count())
        <div>
            <h2 class="font-bold text-zinc-900 dark:text-white mb-3">Matches</h2>
            <div class="space-y-3">
                @foreach($matches as $match)
                <a href="{{ route('matches.show', $match) }}" class="flex items-center justify-between rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4 hover:border-green-400 transition">
                    <span class="font-medium text-zinc-900 dark:text-white">{{ $match->homeClub->name }} vs {{ $match->awayClub->name }}</span>
                    <span class="text-xs text-zinc-400">{{ $match->kick_off->format('M d, Y') }}</span>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        @if(!$users->count() && !$clubs->count() && !$communities->count() && !$posts->count() && !$matches->count())
        <div class="text-center py-12 text-zinc-400">
            <p class="text-4xl mb-3">🔍</p>
            <p class="text-lg">No results found for "{{ $query }}"</p>
            <p class="text-sm mt-1">Try a different search term</p>
        </div>
        @endif
        @endisset
    </div>
</x-layouts::app>
