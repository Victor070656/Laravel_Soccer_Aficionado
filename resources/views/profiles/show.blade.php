<x-layouts::app :title="$user->name">
    <div class="max-w-4xl mx-auto space-y-6">
        {{-- Profile Header --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
            <div class="flex items-start gap-6">
                <div class="flex-shrink-0 w-24 h-24 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center text-green-700 dark:text-green-300 text-3xl font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <div class="flex items-start justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $user->name }}</h1>
                            @if($user->username)
                            <p class="text-zinc-500">{{ '@' . $user->username }}</p>
                            @endif
                        </div>
                        @if(auth()->id() !== $user->id)
                        <form action="{{ route('profiles.follow', $user) }}" method="POST">
                            @csrf
                            <button class="rounded-lg {{ $isFollowing ? 'border border-zinc-300 dark:border-zinc-600 text-zinc-600 dark:text-zinc-400 hover:border-red-300 hover:text-red-500' : 'bg-green-600 text-white hover:bg-green-700' }} px-6 py-2 text-sm font-medium transition">
                                {{ $isFollowing ? 'Following' : 'Follow' }}
                            </button>
                        </form>
                        @endif
                    </div>

                    @if($user->bio)
                    <p class="mt-3 text-zinc-600 dark:text-zinc-400">{{ $user->bio }}</p>
                    @endif

                    <div class="flex items-center gap-6 mt-4">
                        @if($user->country)
                        <span class="text-sm text-zinc-500">📍 {{ $user->country }}</span>
                        @endif
                        <span class="text-sm text-zinc-500">📅 Joined {{ $user->created_at->format('M Y') }}</span>
                    </div>

                    <div class="flex items-center gap-8 mt-4 pt-4 border-t border-zinc-100 dark:border-zinc-700">
                        <div class="text-center">
                            <span class="block text-lg font-bold text-zinc-900 dark:text-white">{{ $postsCount }}</span>
                            <span class="text-xs text-zinc-500">Posts</span>
                        </div>
                        <div class="text-center">
                            <span class="block text-lg font-bold text-zinc-900 dark:text-white">{{ $followersCount }}</span>
                            <span class="text-xs text-zinc-500">Followers</span>
                        </div>
                        <div class="text-center">
                            <span class="block text-lg font-bold text-zinc-900 dark:text-white">{{ $followingCount }}</span>
                            <span class="text-xs text-zinc-500">Following</span>
                        </div>
                        <div class="text-center">
                            <span class="block text-lg font-bold text-green-600">{{ $user->points ?? 0 }}</span>
                            <span class="text-xs text-zinc-500">Points</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Posts --}}
            <div class="lg:col-span-2 space-y-4">
                <h2 class="font-bold text-zinc-900 dark:text-white">Recent Posts</h2>
                @forelse($posts as $post)
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                    <a href="{{ route('posts.show', $post) }}">
                        <p class="text-zinc-700 dark:text-zinc-300 text-sm">{{ Str::limit($post->body, 200) }}</p>
                    </a>
                    @if($post->media)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $post->media[0]) }}" alt="" class="rounded-lg max-h-48 object-cover">
                    </div>
                    @endif
                    <div class="flex items-center gap-4 mt-3 pt-3 border-t border-zinc-100 dark:border-zinc-700">
                        <span class="text-xs text-zinc-400">♥ {{ $post->likes_count }}</span>
                        <span class="text-xs text-zinc-400">💬 {{ $post->comments_count }}</span>
                        <span class="text-xs text-zinc-400 ml-auto">{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <p class="text-sm text-zinc-400">No posts yet.</p>
                @endforelse
                <div>{{ $posts->links() }}</div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Badges --}}
                @if($badges->count())
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                    <h3 class="font-bold text-zinc-900 dark:text-white mb-3">🏅 Badges</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($badges as $badge)
                        <span title="{{ $badge->description }}" class="inline-flex items-center gap-1 rounded-full bg-amber-100 dark:bg-amber-900 px-3 py-1 text-xs font-medium text-amber-700 dark:text-amber-300">
                            {{ $badge->icon ?? '🏆' }} {{ $badge->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Favorite Clubs --}}
                @if($favoriteClubs->count())
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                    <h3 class="font-bold text-zinc-900 dark:text-white mb-3">💚 Favorite Clubs</h3>
                    <div class="space-y-2">
                        @foreach($favoriteClubs as $club)
                        <a href="{{ route('clubs.show', $club) }}" class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300 hover:text-green-600">
                            @if($club->logo)
                            <img src="{{ asset('storage/' . $club->logo) }}" alt="" class="w-6 h-6 rounded">
                            @else
                            <span>⚽</span>
                            @endif
                            {{ $club->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Communities --}}
                @if($communities->count())
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                    <h3 class="font-bold text-zinc-900 dark:text-white mb-3">👥 Communities</h3>
                    <div class="space-y-2">
                        @foreach($communities as $community)
                        <a href="{{ route('communities.show', $community) }}" class="block text-sm text-zinc-700 dark:text-zinc-300 hover:text-green-600">{{ $community->name }}</a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Report --}}
                @if(auth()->id() !== $user->id)
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                    <details>
                        <summary class="text-xs text-zinc-400 cursor-pointer hover:text-red-500">Report user</summary>
                        <form action="{{ route('reports.store') }}" method="POST" class="mt-3 space-y-3">
                            @csrf
                            <input type="hidden" name="reportable_type" value="user">
                            <input type="hidden" name="reportable_id" value="{{ $user->id }}">
                            <select name="reason" required class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white text-sm">
                                <option value="">Select reason</option>
                                <option value="spam">Spam</option>
                                <option value="harassment">Harassment</option>
                                <option value="fake_account">Fake Account</option>
                                <option value="other">Other</option>
                            </select>
                            <textarea name="description" rows="2" placeholder="Details..." class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white text-sm"></textarea>
                            <button type="submit" class="rounded-lg bg-red-600 px-3 py-1.5 text-xs text-white hover:bg-red-700">Submit Report</button>
                        </form>
                    </details>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts::app>
