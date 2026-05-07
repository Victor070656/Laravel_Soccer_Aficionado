<x-layouts::app :title="$user->name">
    <div class="min-h-screen bg-surface py-8">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <!-- Main Profile Card - Glassmorphism -->
            <div class="glass-card rounded-xl p-6 mb-6 relative overflow-hidden">
                <!-- Stadium Glow Effect -->
                <div class="absolute -top-20 -right-20 h-40 w-40 rounded-full bg-primary-container/10 blur-3xl"></div>
                <div class="absolute -bottom-20 -left-20 h-40 w-40 rounded-full bg-primary-container/5 blur-3xl"></div>

                <div class="relative z-10">
                    <!-- Profile Header -->
                    <div class="flex flex-col md:flex-row items-start gap-6">
                        <!-- Profile Picture with Club Badge Overlay -->
                        <div class="relative flex-shrink-0">
                            @if($user->avatar)
                                <img src="{{ $user->avatar_url }}"
                                     alt="{{ $user->name }}"
                                     class="h-24 w-24 rounded-xl object-cover border-2 border-outline-variant/40">
                            @else
                                <div class="flex h-24 w-24 items-center justify-center rounded-xl bg-surface-container-high">
                                    <span class="text-3xl font-bold text-on-surface">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                            @endif

                            <!-- Primary Club Badge Overlay -->
                            @if($user->favoriteClubs->where('pivot.is_primary', true)->first() ?? $user->favoriteClubs->first())
                                @php($primaryClub = $user->favoriteClubs->where('pivot.is_primary', true)->first() ?? $user->favoriteClubs->first())
                                <div class="absolute -bottom-2 -right-2 h-10 w-10 rounded-lg bg-surface-container p-1 shadow-lg border border-outline-variant/40">
                                    <img src="{{ $primaryClub->logo_url ?? '' }}"
                                         alt="{{ $primaryClub->name }}"
                                         class="h-full w-full object-contain">
                                </div>
                            @endif
                        </div>

                        <!-- Profile Info -->
                        <div class="flex-1">
                            <div class="flex flex-col md:flex-row md:items-center gap-3 mb-3">
                                <h1 class="text-headline-lg text-on-surface">{{ $user->name }}</h1>
                                @if($user->username)
                                    <span class="text-label-bold text-on-surface-variant">@{{ $user->username }}</span>
                                @endif
                                <!-- Fan Rank Badge -->
                                <span class="badge-live w-fit">
                                    @if(($user->points ?? 0) >= 10000)
                                        Legendary Fan
                                    @elseif(($user->points ?? 0) >= 5000)
                                        Elite Fan
                                    @elseif(($user->points ?? 0) >= 2500)
                                        Super Fan
                                    @elseif(($user->points ?? 0) >= 1000)
                                        Die-hard Fan
                                    @elseif(($user->points ?? 0) >= 500)
                                        Active Fan
                                    @elseif(($user->points ?? 0) >= 100)
                                        Rising Fan
                                    @else
                                        New Fan
                                    @endif
                                </span>
                            </div>

                            <!-- Football Personality & Location -->
                            <div class="flex flex-wrap gap-4 text-label-sm text-on-surface-variant mb-4">
                                @if($user->football_personality)
                                    <span class="flex items-center gap-1">
                                        <span>✨</span>
                                        {{ $user->football_personality }}
                                    </span>
                                @endif
                                @if($user->country)
                                    <span class="flex items-center gap-1">
                                        <span>📍</span>
                                        {{ $user->country }}@if($user->state), {{ $user->state }}@endif
                                    </span>
                                @endif
                                @if($primaryClub ?? null)
                                    <span class="flex items-center gap-1">
                                        <span>🛡️</span>
                                        {{ $primaryClub->name }} Fan
                                    </span>
                                @endif
                            </div>

                            <!-- Stats Row -->
                            <div class="flex gap-6">
                                <div class="text-center">
                                    <div class="text-headline-md text-primary-container">{{ number_format($user->points ?? 0) }}</div>
                                    <div class="text-label-sm text-on-surface-variant">Points</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-headline-md text-on-surface">{{ $user->followers_count }}</div>
                                    <div class="text-label-sm text-on-surface-variant">Followers</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-headline-md text-on-surface">{{ $user->following_count }}</div>
                                    <div class="text-label-sm text-on-surface-variant">Following</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-headline-md text-on-surface">{{ $user->posts_count }}</div>
                                    <div class="text-label-sm text-on-surface-variant">Posts</div>
                                </div>
                            </div>
                        </div>

                        <!-- Follow Button (if not own profile) -->
                        @if(auth()->check() && auth()->id() !== $user->id)
                            <div class="flex-shrink-0">
                                <form action="{{ route('users.follow', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="rounded-xl px-6 py-2.5 text-sm font-semibold transition-all hover:scale-105
                                            {{ $isFollowing
                                                ? 'border-2 border-outline-variant/20 text-on-surface-variant hover:border-secondary hover:text-secondary'
                                                : 'bg-primary-container text-on-primary-container shadow-lg shadow-primary-container/20 hover:bg-primary-container/90' }}">
                                        {{ $isFollowing ? '✓ Following' : '+ Follow' }}
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <!-- Bio -->
                    @if($user->bio)
                        <div class="mt-6 p-4 bg-surface-container/50 rounded-lg">
                            <p class="text-body-md text-on-surface">{{ $user->bio }}</p>
                        </div>
                    @endif

                    <!-- Football Identity Details -->
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Favorite Player -->
                        @if($user->favoritePlayer)
                            <div class="flex items-center gap-3 p-3 bg-surface-container/30 rounded-lg">
                                @if($user->favoritePlayer->photo_url)
                                    <img src="{{ $user->favoritePlayer->photo_url }}"
                                         alt="{{ $user->favoritePlayer->name }}"
                                         class="h-12 w-12 rounded-lg object-cover">
                                @else
                                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-surface-container-high">
                                        <span class="text-xl">👤</span>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-label-bold text-on-surface">Favorite Player</div>
                                    <div class="text-body-md text-primary-container">{{ $user->favoritePlayer->name }}</div>
                                    @if($user->favoritePlayer->club)
                                        <div class="text-label-sm text-on-surface-variant">{{ $user->favoritePlayer->club->name }}</div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Favorite Coach -->
                        @if($user->favorite_coach)
                            <div class="flex items-center gap-3 p-3 bg-surface-container/30 rounded-lg">
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-surface-container-high">
                                    <span class="text-xl">🎓</span>
                                </div>
                                <div>
                                    <div class="text-label-bold text-on-surface">Favorite Coach</div>
                                    <div class="text-body-md text-primary-container">{{ $user->favorite_coach }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Second Row: Activity & Achievements -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Fan Ranking -->
                <div class="glass-card rounded-xl p-6">
                    <h3 class="text-headline-md text-on-surface mb-4 flex items-center gap-2">
                        <span>🏆</span>
                        Fan Ranking
                    </h3>
                    <div class="text-center py-4">
                        @php($points = $user->points ?? 0)
                        <div class="text-display-xl
                            @if($points >= 10000) text-primary-container
                            @elseif($points >= 5000) text-tertiary-fixed-dim
                            @elseif($points >= 2500) text-secondary
                            @elseif($points >= 1000) text-on-surface
                            @elseif($points >= 500) text-on-surface-variant
                            @else text-outline @endif">
                            @if($points >= 10000)
                                Legendary Fan
                            @elseif($points >= 5000)
                                Elite Fan
                            @elseif($points >= 2500)
                                Super Fan
                            @elseif($points >= 1000)
                                Die-hard Fan
                            @elseif($points >= 500)
                                Active Fan
                            @elseif($points >= 100)
                                Rising Fan
                            @else
                                New Fan
                            @endif
                        </div>
                        <div class="text-label-bold text-on-surface-variant mt-1">{{ number_format($points) }} Points</div>
                        <!-- Progress Bar -->
                        <div class="mt-4 h-2 bg-surface-container-high rounded-full overflow-hidden">
                            <div class="h-full bg-primary-container rounded-full transition-all duration-1000"
                                 style="width: {{ min(100, $points / 100) }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Badges -->
                @if($user->badges->count())
                    <div class="glass-card rounded-xl p-6 lg:col-span-2">
                        <h3 class="text-headline-md text-on-surface mb-4 flex items-center gap-2">
                            <span>🏅</span>
                            Achievements
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($user->badges as $badge)
                                <div class="flex items-center gap-2 p-2 bg-surface-container/50 rounded-lg" title="{{ $badge->name }}">
                                    <span class="text-xl">{{ $badge->icon ?? '🏅' }}</span>
                                    <span class="text-label-sm text-on-surface">{{ $badge->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Posts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-5">
                    <h2 class="font-bold text-lg text-on-surface flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-primary-container/20 flex items-center justify-center text-primary-container text-xs">📝</span>
                        Recent Posts
                    </h2>
                    @forelse($posts as $post)
                        <div class="rounded-2xl border border-outline-variant/20 bg-surface-container p-5 shadow-sm hover:shadow-md transition-all duration-300">
                            <a href="{{ route('posts.show', $post) }}" class="block">
                                <p class="text-on-surface text-sm leading-relaxed">{{ Str::limit($post->body, 200) }}</p>
                            </a>
                            @if($post->media)
                                <div class="mt-3 rounded-xl overflow-hidden">
                                    <img loading="lazy" decoding="async" src="{{ asset('storage/' . $post->media[0]) }}" alt="" class="rounded-xl max-h-52 w-full object-cover hover:opacity-90 transition-opacity">
                                </div>
                            @endif
                            <div class="flex items-center gap-4 mt-4 pt-3 border-t border-outline-variant/20">
                                <span class="inline-flex items-center gap-1.5 text-xs text-primary-container">
                                    <span>❤️</span>
                                    {{ $post->likes_count }}
                                </span>
                                <span class="inline-flex items-center gap-1.5 text-xs text-on-surface-variant">💬 {{ $post->comments_count }}</span>
                                <span class="text-xs text-on-surface-variant ml-auto">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border-2 border-dashed border-outline-variant/20 p-10 text-center">
                            <p class="text-sm text-on-surface-variant">No posts yet.</p>
                        </div>
                    @endforelse
                    <div>{{ $posts->links() }}</div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-5">
                    @if($user->favoriteClubs->count())
                        <div class="rounded-2xl border border-outline-variant/20 bg-surface-container overflow-hidden shadow-sm">
                            <div class="px-5 py-4 bg-gradient-to-r from-primary-container/20 to-primary-container/10 border-b border-outline-variant/20">
                                <h3 class="font-bold text-sm text-on-surface flex items-center gap-2">💚 Favorite Clubs</h3>
                            </div>
                            <div class="p-4 space-y-2">
                                @foreach($user->favoriteClubs as $club)
                                    <a href="{{ $club->api_team_id ? route('clubs.show', $club->api_team_id) : '#' }}"
                                       class="flex items-center gap-3 p-2 rounded-xl text-sm text-on-surface hover:text-primary-container hover:bg-primary-container/10 transition-all">
                                        @if($club->logo_url)
                                            <img loading="lazy" decoding="async" src="{{ $club->logo_url }}" alt="" class="w-7 h-7 rounded-lg object-contain">
                                        @else
                                            <span class="w-7 h-7 rounded-lg bg-primary-container/20 flex items-center justify-center text-sm">⚽</span>
                                        @endif
                                        <span class="font-medium">{{ $club->name }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($user->communities->count())
                        <div class="rounded-2xl border border-outline-variant/20 bg-surface-container overflow-hidden shadow-sm">
                            <div class="px-5 py-4 bg-gradient-to-r from-secondary/20 to-secondary/10 border-b border-outline-variant/20">
                                <h3 class="font-bold text-sm text-on-surface flex items-center gap-2">👥 Communities</h3>
                            </div>
                            <div class="p-4 space-y-1">
                                @foreach($user->communities as $community)
                                    <a href="{{ route('communities.show', $community) }}"
                                       class="block p-2 rounded-xl text-sm text-on-surface hover:text-primary-container hover:bg-primary-container/10 transition-all font-medium">{{ $community->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(auth()->check() && auth()->id() !== $user->id)
                        <div class="rounded-2xl border border-outline-variant/20 bg-surface-container p-4 shadow-sm">
                            <details>
                                <summary class="text-xs text-on-surface-variant cursor-pointer hover:text-secondary transition-colors">⚠️ Report user</summary>
                                <form action="{{ route('reports.store') }}" method="POST" class="mt-4 space-y-3">
                                    @csrf
                                    <input type="hidden" name="reportable_type" value="user">
                                    <input type="hidden" name="reportable_id" value="{{ $user->id }}">
                                    <select name="reason" required class="w-full rounded-xl border-outline-variant/20 bg-surface-container-high text-on-surface text-sm focus:border-primary-container focus:ring-primary-container/20">
                                        <option value="">Select reason</option>
                                        <option value="spam">Spam</option>
                                        <option value="harassment">Harassment</option>
                                        <option value="fake_account">Fake Account</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <textarea name="description" rows="2" placeholder="Details..." class="w-full rounded-xl border-outline-variant/20 bg-surface-container-high text-on-surface text-sm focus:border-primary-container focus:ring-primary-container/20"></textarea>
                                    <button type="submit" class="rounded-xl bg-secondary px-4 py-2 text-xs font-semibold text-on-secondary hover:bg-secondary/90 transition-all">Submit Report</button>
                                </form>
                            </details>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
