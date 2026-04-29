<x-layouts::app :title="$user->name">
    <div class="max-w-5xl mx-auto space-y-6 p-2 sm:p-4">
        {{-- Profile Header --}}
        <div class="relative rounded-2xl overflow-hidden border border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container shadow-lg glass-edge">
            {{-- Banner --}}
            <div class="h-36 sm:h-48 bg-gradient-to-br from-primary via-primary/80 to-primary/60 relative">
                <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 24px 24px;"></div>
                <div class="absolute top-0 right-0 w-80 h-80 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            </div>

            <div class="relative px-6 sm:px-8 pb-6">
                <div class="flex flex-col sm:flex-row items-center sm:items-end gap-4 -mt-12 sm:-mt-16">
                    {{-- Avatar --}}
                    @if($user->avatar)
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="shrink-0 w-24 h-24 sm:w-32 sm:h-32 rounded-2xl object-cover border-4 border-white dark:border-zinc-800 shadow-xl">
                    @else
                    <div class="shrink-0 w-24 h-24 sm:w-32 sm:h-32 rounded-2xl bg-gradient-to-br from-primary to-primary/60 flex items-center justify-center text-on-primary text-3xl sm:text-4xl font-bold border-4 border-white dark:border-zinc-800 shadow-xl">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    @endif
                    <div class="flex-1 text-center sm:text-left pb-2">
                        <div class="flex flex-col sm:flex-row items-center gap-3">
                            <div>
                                <h1 class="text-2xl sm:text-3xl font-bold text-on-surface">{{ $user->name }}</h1>
                                @if($user->username)
                                <p class="text-on-surface-variant text-sm">{{ '@' . $user->username }}</p>
                                @endif
                            </div>
                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('users.follow', $user) }}" method="POST" class="sm:ml-auto">
                                @csrf
                                <button class="rounded-xl {{ $isFollowing ? 'border-2 border-outline-variant/20 dark:border-outline-variant/30 text-on-surface-variant dark:text-on-surface-variant hover:border-secondary hover:text-secondary dark:hover:border-secondary dark:hover:text-secondary' : 'bg-gradient-to-r from-primary to-primary/80 text-on-primary shadow-lg shadow-primary/20 hover:from-primary/90 hover:to-primary/70' }} px-6 py-2.5 text-sm font-semibold transition-all hover:scale-105">
                                    {{ $isFollowing ? '✓ Following' : '+ Follow' }}
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>

                @if($user->bio)
                <p class="mt-4 text-on-surface leading-relaxed max-w-2xl">{{ $user->bio }}</p>
                @endif

                <div class="flex flex-wrap items-center gap-4 mt-4 text-sm text-on-surface-variant">
                    @if($user->country)<span class="flex items-center gap-1.5">📍 {{ $user->country }}</span>@endif
                    <span class="flex items-center gap-1.5">📅 Joined {{ $user->created_at->format('M Y') }}</span>
                </div>

                <div class="grid grid-cols-4 gap-4 mt-6 pt-6 border-t border-outline-variant/20 dark:border-outline-variant/30">
                    @foreach([['count' => $user->posts_count, 'label' => 'Posts', 'color' => 'text-on-surface'],['count' => $user->followers_count, 'label' => 'Followers', 'color' => 'text-on-surface'],['count' => $user->following_count, 'label' => 'Following', 'color' => 'text-on-surface'],['count' => $user->points ?? 0, 'label' => 'Points', 'color' => 'text-primary']] as $stat)
                    <div class="text-center">
                        <span class="block text-xl sm:text-2xl font-bold {{ $stat['color'] }}">{{ $stat['count'] }}</span>
                        <span class="text-xs text-on-surface-variant">{{ $stat['label'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Posts --}}
            <div class="lg:col-span-2 space-y-5">
                <h2 class="font-bold text-lg text-on-surface flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-primary/20 flex items-center justify-center text-primary text-xs">📝</span>
                    Recent Posts
                </h2>
                @forelse($posts as $post)
                <div class="rounded-2xl border border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container p-5 shadow-sm hover:shadow-md transition-all duration-300 glass-edge">
                    <a href="{{ route('posts.show', $post) }}" class="block">
                        <p class="text-on-surface text-sm leading-relaxed">{{ Str::limit($post->body, 200) }}</p>
                    </a>
                    @if($post->media)
                    <div class="mt-3 rounded-xl overflow-hidden">
                        <img loading="lazy" decoding="async" src="{{ asset('storage/' . $post->media[0]) }}" alt="" class="rounded-xl max-h-52 w-full object-cover hover:opacity-90 transition-opacity">
                    </div>
                    @endif
                    <div class="flex items-center gap-4 mt-4 pt-3 border-t border-outline-variant/20 dark:border-outline-variant/30">
                        <span class="inline-flex items-center gap-1.5 text-xs text-primary">
                            <svg class="w-3.5 h-3.5 text-primary" fill="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            {{ $post->likes_count }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-xs text-on-surface-variant">💬 {{ $post->comments_count }}</span>
                        <span class="text-xs text-on-surface-variant ml-auto">{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <div class="rounded-2xl border-2 border-dashed border-outline-variant/20 dark:border-outline-variant/30 p-10 text-center">
                    <p class="text-sm text-on-surface-variant">No posts yet.</p>
                </div>
                @endforelse
                <div>{{ $posts->links() }}</div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-5">
                @if($user->badges->count())
                <div class="rounded-2xl border border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container overflow-hidden shadow-sm glass-edge">
                    <div class="px-5 py-4 bg-gradient-to-r from-tertiary/20 to-tertiary/10 dark:from-tertiary/25 dark:to-tertiary/15 border-b border-outline-variant/20 dark:border-outline-variant/30">
                        <h3 class="font-bold text-sm text-on-surface flex items-center gap-2">🏅 Badges</h3>
                    </div>
                    <div class="p-4">
                        <div class="flex flex-wrap gap-2">
                            @foreach($user->badges as $badge)
                            <span title="{{ $badge->description }}" class="inline-flex items-center gap-1.5 rounded-full bg-gradient-to-r from-tertiary/30 to-tertiary/20 dark:from-tertiary/25 dark:to-tertiary/15 px-3 py-1.5 text-xs font-medium text-on-surface border border-tertiary/20 dark:border-tertiary/30 hover:scale-105 transition-transform cursor-default">
                                {{ $badge->icon ?? '🏆' }} {{ $badge->name }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                @if($user->favoriteClubs->count())
                <div class="rounded-2xl border border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container overflow-hidden shadow-sm glass-edge">
                    <div class="px-5 py-4 bg-gradient-to-r from-primary/20 to-primary/10 dark:from-primary/25 dark:to-primary/15 border-b border-outline-variant/20 dark:border-outline-variant/30">
                        <h3 class="font-bold text-sm text-on-surface flex items-center gap-2">💚 Favorite Clubs</h3>
                    </div>
                    <div class="p-4 space-y-2">
                        @foreach($user->favoriteClubs as $club)
                        <a href="{{ $club->api_team_id ? route('clubs.show', $club->api_team_id) : '#' }}" class="flex items-center gap-3 p-2 rounded-xl text-sm text-on-surface hover:text-primary hover:bg-primary/10 dark:hover:bg-primary/15 transition-all">
                            @if($club->logo_url)<img loading="lazy" decoding="async" src="{{ $club->logo_url }}" alt="" class="w-7 h-7 rounded-lg object-contain">@else<span class="w-7 h-7 rounded-lg bg-primary/20 flex items-center justify-center text-sm">⚽</span>@endif
                            <span class="font-medium">{{ $club->name }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($user->communities->count())
                <div class="rounded-2xl border border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container overflow-hidden shadow-sm glass-edge">
                    <div class="px-5 py-4 bg-gradient-to-r from-secondary/20 to-secondary/10 dark:from-secondary/25 dark:to-secondary/15 border-b border-outline-variant/20 dark:border-outline-variant/30">
                        <h3 class="font-bold text-sm text-on-surface flex items-center gap-2">👥 Communities</h3>
                    </div>
                    <div class="p-4 space-y-1">
                        @foreach($user->communities as $community)
                        <a href="{{ route('communities.show', $community) }}" class="block p-2 rounded-xl text-sm text-on-surface hover:text-primary hover:bg-primary/10 dark:hover:bg-primary/15 transition-all font-medium">{{ $community->name }}</a>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(auth()->id() !== $user->id)
                <div class="rounded-2xl border border-outline-variant/20 dark:border-outline-variant/30 bg-surface-container dark:bg-surface-container p-4 shadow-sm glass-edge">
                    <details>
                        <summary class="text-xs text-on-surface-variant cursor-pointer hover:text-secondary transition-colors">⚠️ Report user</summary>
                        <form action="{{ route('reports.store') }}" method="POST" class="mt-4 space-y-3">
                            @csrf
                            <input type="hidden" name="reportable_type" value="user">
                            <input type="hidden" name="reportable_id" value="{{ $user->id }}">
                            <select name="reason" required class="w-full rounded-xl border-outline-variant/20 dark:border-outline-variant/30 dark:bg-surface-container-high dark:text-on-surface text-sm focus:border-secondary focus:ring-secondary/20">
                                <option value="">Select reason</option>
                                <option value="spam">Spam</option>
                                <option value="harassment">Harassment</option>
                                <option value="fake_account">Fake Account</option>
                                <option value="other">Other</option>
                            </select>
                            <textarea name="description" rows="2" placeholder="Details..." class="w-full rounded-xl border-outline-variant/20 dark:border-outline-variant/30 dark:bg-surface-container-high dark:text-on-surface text-sm focus:border-secondary focus:ring-secondary/20"></textarea>
                            <button type="submit" class="rounded-xl bg-secondary px-4 py-2 text-xs font-semibold text-on-secondary hover:bg-secondary/90 transition-all">Submit Report</button>
                        </form>
                    </details>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts::app>
