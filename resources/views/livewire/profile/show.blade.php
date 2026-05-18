<div class="min-h-screen bg-surface py-8">
    <!-- Football Identity Card Section -->
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
                        @if($user->avatar_url)
                            <img src="{{ $user->avatar_url }}"
                                 alt="{{ $user->name }}"
                                 class="h-24 w-24 rounded-xl object-cover border-2 border-outline-variant/40">
                        @else
                            <div class="flex h-24 w-24 items-center justify-center rounded-xl bg-surface-container-high text-display-xl text-on-surface">
                                {{ $user->initials() }}
                            </div>
                        @endif

                        <!-- Primary Club Badge Overlay -->
                        @if($primaryClub)
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
                            <span class="badge-live w-fit">{{ $fanRank }}</span>
                        </div>

                        <!-- Football Personality & Location -->
                        <div class="flex flex-wrap gap-4 text-label-sm text-on-surface-variant mb-4">
                            @if($user->football_personality)
                                <span class="flex items-center gap-1">
                                    <flux:icon icon="sparkles" class="h-4 w-4 text-primary-container"/>
                                    {{ $user->football_personality }}
                                </span>
                            @endif
                            @if($user->country)
                                <span class="flex items-center gap-1">
                                    <flux:icon icon="map-pin" class="h-4 w-4"/>
                                    {{ $user->country }}@if($user->state), {{ $user->state }}@endif
                                </span>
                            @endif
                            @if($primaryClub)
                                <span class="flex items-center gap-1">
                                    <flux:icon icon="shield-check" class="h-4 w-4 text-primary-container"/>
                                    {{ $primaryClub->name }} Fan
                                </span>
                            @endif
                        </div>

                        <!-- Stats Row -->
                        <div class="flex gap-6">
                            <div class="text-center">
                                <div class="text-headline-md text-primary-container">{{ $user->points ?? 0 }}</div>
                                <div class="text-label-sm text-on-surface-variant">Points</div>
                            </div>
                            <div class="text-center">
                                <div class="text-headline-md text-on-surface">{{ $user->followers->count() }}</div>
                                <div class="text-label-sm text-on-surface-variant">Followers</div>
                            </div>
                            <div class="text-center">
                                <div class="text-headline-md text-on-surface">{{ $user->following->count() }}</div>
                                <div class="text-label-sm text-on-surface-variant">Following</div>
                            </div>
                            <div class="text-center">
                                <div class="text-headline-md text-on-surface">{{ $user->posts->count() }}</div>
                                <div class="text-label-sm text-on-surface-variant">Posts</div>
                            </div>
                        </div>
                    </div>

                    <!-- Follow Button (if not own profile) -->
                    @if(auth()->check() && auth()->id() !== $user->id)
                        <div class="flex-shrink-0">
                            @if(auth()->user()->isFollowing($user))
                                <flux:button variant="outline" size="sm">Following</flux:button>
                            @else
                                <flux:button variant="primary" size="sm">Follow</flux:button>
                            @endif
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
                                    <flux:icon icon="user" class="h-6 w-6 text-on-surface-variant"/>
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
                                <flux:icon icon="academic-cap" class="h-6 w-6 text-on-surface-variant"/>
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
            <!-- Matchday Activity -->
            <div class="glass-card rounded-xl p-6">
                <h3 class="text-headline-md text-on-surface mb-4 flex items-center gap-2">
                    <flux:icon icon="bolt" class="h-5 w-5 text-primary-container"/>
                    Matchday Activity
                </h3>
                <div class="text-center py-4">
                    <div class="text-display-xl text-primary-container">{{ $matchdayStreak }}</div>
                    <div class="text-label-bold text-on-surface-variant mt-1">Day Streak</div>
                    <div class="mt-3 text-label-sm text-on-surface-variant">
                        Engagement Score: <span class="text-primary-container font-bold">{{ $engagementScore }}</span>
                    </div>
                </div>
            </div>

            <!-- Fan Ranking -->
            <div class="glass-card rounded-xl p-6">
                <h3 class="text-headline-md text-on-surface mb-4 flex items-center gap-2">
                    <flux:icon icon="trophy" class="h-5 w-5 text-primary-container"/>
                    Fan Ranking
                </h3>
                <div class="text-center py-4">
                    <div class="text-display-xl {{ $fanRankColor }}">{{ $fanRank }}</div>
                    <div class="text-label-bold text-on-surface-variant mt-1">{{ number_format($user->points ?? 0) }} Points</div>
                    <!-- Progress Bar -->
                    <div class="mt-4 h-2 bg-surface-container-high rounded-full overflow-hidden">
                        <div class="h-full bg-primary-container rounded-full transition-all duration-1000"
                             style="width: {{ min(100, ($user->points ?? 0) / 100) }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Achievements/Badges -->
            <div class="glass-card rounded-xl p-6">
                <h3 class="text-headline-md text-on-surface mb-4 flex items-center gap-2">
                    <flux:icon icon="star" class="h-5 w-5 text-primary-container"/>
                    Achievements
                </h3>
                <div class="flex flex-wrap gap-2">
                    @forelse($badges as $badge)
                        <div class="flex items-center gap-2 p-2 bg-surface-container/50 rounded-lg" title="{{ $badge->name }}">
                            <span class="text-xl">{{ $badge->icon ?? '🏅' }}</span>
                            <span class="text-label-sm text-on-surface">{{ $badge->name }}</span>
                        </div>
                    @empty
                        <div class="text-label-sm text-on-surface-variant text-center w-full py-4">
                            No badges yet. Start engaging to earn achievements!
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Posts -->
            <div class="glass-card rounded-xl p-6">
                <h3 class="text-headline-md text-on-surface mb-4 flex items-center gap-2">
                    <flux:icon icon="document-text" class="h-5 w-5 text-primary-container"/>
                    Recent Posts
                </h3>
                <div class="space-y-3">
                    @forelse($recentPosts as $post)
                        <div class="p-3 bg-surface-container/30 rounded-lg">
                            <p class="text-body-md text-on-surface line-clamp-2">{{ Str::limit($post->content, 100) }}</p>
                            <div class="mt-2 text-label-sm text-on-surface-variant">
                                {{ $post->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @empty
                        <div class="text-label-sm text-on-surface-variant text-center py-4">
                            No posts yet.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Comments -->
            <div class="glass-card rounded-xl p-6">
                <h3 class="text-headline-md text-on-surface mb-4 flex items-center gap-2">
                    <flux:icon icon="chat-bubble-bottom-center" class="h-5 w-5 text-primary-container"/>
                    Recent Comments
                </h3>
                <div class="space-y-3">
                    @forelse($recentComments as $comment)
                        <div class="p-3 bg-surface-container/30 rounded-lg">
                            <p class="text-body-md text-on-surface line-clamp-2">{{ Str::limit($comment->content, 100) }}</p>
                            <div class="mt-2 text-label-sm text-on-surface-variant">
                                On: {{ $comment->post->title ?? 'Post' }} • {{ $comment->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @empty
                        <div class="text-label-sm text-on-surface-variant text-center py-4">
                            No comments yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Top Communities -->
        @if($topCommunities && $topCommunities->count() > 0)
            <div class="mt-6 glass-card rounded-xl p-6">
                <h3 class="text-headline-md text-on-surface mb-4 flex items-center gap-2">
                    <flux:icon icon="user-group" class="h-5 w-5 text-primary-container"/>
                    Top Communities
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($topCommunities as $community)
                        <a href="{{ route('communities.show', $community) }}"
                           class="flex items-center gap-3 p-3 bg-surface-container/30 rounded-lg hover:bg-surface-container/50 transition-colors">
                            @if($community->logo_url)
                                <img src="{{ $community->logo_url }}" alt="{{ $community->name }}" class="h-10 w-10 rounded-lg object-contain">
                            @else
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-surface-container-high">
                                    <flux:icon icon="user-group" class="h-5 w-5 text-on-surface-variant"/>
                                </div>
                            @endif
                            <div>
                                <div class="text-label-bold text-on-surface">{{ $community->name }}</div>
                                <div class="text-label-sm text-on-surface-variant">{{ $community->pivot->role ?? 'Member' }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
