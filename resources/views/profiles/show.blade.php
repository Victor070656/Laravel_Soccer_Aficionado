<x-layouts::app :title="$user->name">
    <div class="max-w-5xl mx-auto space-y-6 p-2 sm:p-4">
        {{-- Profile Header --}}
        <div class="relative rounded-2xl overflow-hidden border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-lg">
            {{-- Banner --}}
            <div class="h-36 sm:h-48 bg-gradient-to-br from-green-500 via-emerald-500 to-teal-600 relative">
                <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 24px 24px;"></div>
                <div class="absolute top-0 right-0 w-80 h-80 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            </div>

            <div class="relative px-6 sm:px-8 pb-6">
                <div class="flex flex-col sm:flex-row items-center sm:items-end gap-4 -mt-12 sm:-mt-16">
                    {{-- Avatar --}}
                    <div class="flex-shrink-0 w-24 h-24 sm:w-32 sm:h-32 rounded-2xl bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white text-3xl sm:text-4xl font-bold border-4 border-white dark:border-zinc-800 shadow-xl">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 text-center sm:text-left pb-2">
                        <div class="flex flex-col sm:flex-row items-center gap-3">
                            <div>
                                <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white">{{ $user->name }}</h1>
                                @if($user->username)
                                <p class="text-zinc-500 text-sm">{{ '@' . $user->username }}</p>
                                @endif
                            </div>
                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('profiles.follow', $user) }}" method="POST" class="sm:ml-auto">
                                @csrf
                                <button class="rounded-xl {{ $isFollowing ? 'border-2 border-zinc-200 dark:border-zinc-600 text-zinc-600 dark:text-zinc-400 hover:border-red-300 hover:text-red-500 dark:hover:border-red-700 dark:hover:text-red-400' : 'bg-gradient-to-r from-green-600 to-emerald-500 text-white shadow-lg shadow-green-600/20 hover:from-green-500 hover:to-emerald-400' }} px-6 py-2.5 text-sm font-semibold transition-all hover:scale-105">
                                    {{ $isFollowing ? '✓ Following' : '+ Follow' }}
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>

                @if($user->bio)
                <p class="mt-4 text-zinc-600 dark:text-zinc-400 leading-relaxed max-w-2xl">{{ $user->bio }}</p>
                @endif

                <div class="flex flex-wrap items-center gap-4 mt-4 text-sm text-zinc-500">
                    @if($user->country)<span class="flex items-center gap-1.5">📍 {{ $user->country }}</span>@endif
                    <span class="flex items-center gap-1.5">📅 Joined {{ $user->created_at->format('M Y') }}</span>
                </div>

                <div class="grid grid-cols-4 gap-4 mt-6 pt-6 border-t border-zinc-100 dark:border-zinc-700/50">
                    @foreach([['count' => $postsCount, 'label' => 'Posts', 'color' => 'text-zinc-900 dark:text-white'],['count' => $followersCount, 'label' => 'Followers', 'color' => 'text-zinc-900 dark:text-white'],['count' => $followingCount, 'label' => 'Following', 'color' => 'text-zinc-900 dark:text-white'],['count' => $user->points ?? 0, 'label' => 'Points', 'color' => 'text-green-600']] as $stat)
                    <div class="text-center">
                        <span class="block text-xl sm:text-2xl font-bold {{ $stat['color'] }}">{{ $stat['count'] }}</span>
                        <span class="text-xs text-zinc-500">{{ $stat['label'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Posts --}}
            <div class="lg:col-span-2 space-y-5">
                <h2 class="font-bold text-lg text-zinc-900 dark:text-white flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 text-xs">📝</span>
                    Recent Posts
                </h2>
                @forelse($posts as $post)
                <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 p-5 shadow-sm hover:shadow-md transition-all duration-300">
                    <a href="{{ route('posts.show', $post) }}" class="block">
                        <p class="text-zinc-700 dark:text-zinc-300 text-sm leading-relaxed">{{ Str::limit($post->body, 200) }}</p>
                    </a>
                    @if($post->media)
                    <div class="mt-3 rounded-xl overflow-hidden">
                        <img loading="lazy" decoding="async" src="{{ asset('storage/' . $post->media[0]) }}" alt="" class="rounded-xl max-h-52 w-full object-cover hover:opacity-90 transition-opacity">
                    </div>
                    @endif
                    <div class="flex items-center gap-4 mt-4 pt-3 border-t border-zinc-100 dark:border-zinc-700/50">
                        <span class="inline-flex items-center gap-1.5 text-xs text-zinc-400">
                            <svg class="w-3.5 h-3.5 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            {{ $post->likes_count }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-xs text-zinc-400">💬 {{ $post->comments_count }}</span>
                        <span class="text-xs text-zinc-400 ml-auto">{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <div class="rounded-2xl border-2 border-dashed border-zinc-200 dark:border-zinc-700 p-10 text-center">
                    <p class="text-sm text-zinc-400">No posts yet.</p>
                </div>
                @endforelse
                <div>{{ $posts->links() }}</div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-5">
                @if($badges->count())
                <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 overflow-hidden shadow-sm">
                    <div class="px-5 py-4 bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-amber-900/20 dark:to-yellow-900/20 border-b border-zinc-100 dark:border-zinc-700/50">
                        <h3 class="font-bold text-sm text-zinc-900 dark:text-white flex items-center gap-2">🏅 Badges</h3>
                    </div>
                    <div class="p-4">
                        <div class="flex flex-wrap gap-2">
                            @foreach($badges as $badge)
                            <span title="{{ $badge->description }}" class="inline-flex items-center gap-1.5 rounded-full bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-amber-900/30 dark:to-yellow-900/30 px-3 py-1.5 text-xs font-medium text-amber-700 dark:text-amber-300 border border-amber-200/50 dark:border-amber-800/50 hover:scale-105 transition-transform cursor-default">
                                {{ $badge->icon ?? '🏆' }} {{ $badge->name }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                @if($favoriteClubs->count())
                <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 overflow-hidden shadow-sm">
                    <div class="px-5 py-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-b border-zinc-100 dark:border-zinc-700/50">
                        <h3 class="font-bold text-sm text-zinc-900 dark:text-white flex items-center gap-2">💚 Favorite Clubs</h3>
                    </div>
                    <div class="p-4 space-y-2">
                        @foreach($favoriteClubs as $club)
                        <a href="{{ route('clubs.show', $club) }}" class="flex items-center gap-3 p-2 rounded-xl text-sm text-zinc-700 dark:text-zinc-300 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 transition-all">
                            @if($club->logo)<img loading="lazy" decoding="async" src="{{ asset('storage/' . $club->logo) }}" alt="" class="w-7 h-7 rounded-lg object-contain">@else<span class="w-7 h-7 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-sm">⚽</span>@endif
                            <span class="font-medium">{{ $club->name }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($communities->count())
                <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 overflow-hidden shadow-sm">
                    <div class="px-5 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-b border-zinc-100 dark:border-zinc-700/50">
                        <h3 class="font-bold text-sm text-zinc-900 dark:text-white flex items-center gap-2">👥 Communities</h3>
                    </div>
                    <div class="p-4 space-y-1">
                        @foreach($communities as $community)
                        <a href="{{ route('communities.show', $community) }}" class="block p-2 rounded-xl text-sm text-zinc-700 dark:text-zinc-300 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 transition-all font-medium">{{ $community->name }}</a>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(auth()->id() !== $user->id)
                <div class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 p-4 shadow-sm">
                    <details>
                        <summary class="text-xs text-zinc-400 cursor-pointer hover:text-red-500 transition-colors">⚠️ Report user</summary>
                        <form action="{{ route('reports.store') }}" method="POST" class="mt-4 space-y-3">
                            @csrf
                            <input type="hidden" name="reportable_type" value="user">
                            <input type="hidden" name="reportable_id" value="{{ $user->id }}">
                            <select name="reason" required class="w-full rounded-xl border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white text-sm focus:border-red-500 focus:ring-red-500/20">
                                <option value="">Select reason</option>
                                <option value="spam">Spam</option>
                                <option value="harassment">Harassment</option>
                                <option value="fake_account">Fake Account</option>
                                <option value="other">Other</option>
                            </select>
                            <textarea name="description" rows="2" placeholder="Details..." class="w-full rounded-xl border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white text-sm focus:border-red-500 focus:ring-red-500/20"></textarea>
                            <button type="submit" class="rounded-xl bg-red-600 px-4 py-2 text-xs font-semibold text-white hover:bg-red-700 transition-all">Submit Report</button>
                        </form>
                    </details>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts::app>
