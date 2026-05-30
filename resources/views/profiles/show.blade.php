<x-layouts::app :title="$user->name">
    <div class="min-h-screen bg-surface py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <!-- Main Profile Card - Glassmorphism -->
            <div class="glass-card rounded-2xl p-6 sm:p-8 mb-8 relative overflow-hidden glass-edge">
                <!-- Stadium Glow Effect -->
                <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-primary-container/10 blur-3xl animate-pulse-glow"></div>
                <div class="absolute -bottom-24 -left-24 h-64 w-64 rounded-full bg-primary-container/5 blur-3xl animate-pulse-glow animation-delay-500"></div>

                <div class="relative z-10">
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                        <!-- Profile Picture with Club Badge Overlay -->
                        <div class="relative flex-shrink-0 group">
                            @if($user->avatar)
                                <img src="{{ $user->avatar_url }}"
                                     alt="{{ $user->name }}"
                                     class="h-32 w-32 rounded-2xl object-cover border-2 border-outline-variant/40 shadow-2xl transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div class="flex h-32 w-32 items-center justify-center rounded-2xl bg-surface-container-high border-2 border-outline-variant/40 shadow-2xl">
                                    <span class="text-4xl font-bold text-on-surface">{{ $user->initials() }}</span>
                                </div>
                            @endif

                            <!-- Primary Club Badge Overlay -->
                            @php($primaryClub = $user->favoriteClubs->where('pivot.is_primary', true)->first() ?? $user->favoriteClubs->first())
                            @if($primaryClub)
                                <div class="absolute -bottom-3 -right-3 h-12 w-12 rounded-xl bg-surface-container-high p-1.5 shadow-xl border border-outline-variant/40 animate-bounce-subtle">
                                    <img src="{{ $primaryClub->logo_url ?? '' }}"
                                         alt="{{ $primaryClub->name }}"
                                         class="h-full w-full object-contain">
                                </div>
                            @endif
                        </div>

                        <!-- Profile Info -->
                        <div class="flex-1 text-center md:text-left w-full">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                                <div>
                                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mb-1">
                                        <h1 class="text-headline-lg text-on-surface">{{ $user->name }}</h1>
                                        <span class="badge-live">
                                            @php($points = $user->points ?? 0)
                                            @if($points >= 10000) Legend
                                            @elseif($points >= 5000) Elite
                                            @elseif($points >= 2500) Super
                                            @elseif($points >= 1000) Die-hard
                                            @elseif($points >= 500) Active
                                            @elseif($points >= 100) Rising
                                            @else Rookie @endif
                                        </span>
                                    </div>
                                    @if($user->username)
                                        <div class="text-label-bold text-primary-container">{{ $user->username }}</div>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center justify-center gap-3">
                                    @if(auth()->check() && auth()->id() !== $user->id)
                                        <form action="{{ route('users.follow', $user) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="rounded-xl px-8 py-3 text-sm font-bold transition-all hover:scale-105 active:scale-95
                                                    {{ $isFollowing
                                                        ? 'bg-surface-container text-on-surface-variant border border-outline-variant/40 hover:bg-surface-container-high'
                                                        : 'bg-primary-container text-on-primary-container shadow-lg shadow-primary-container/20 hover:animate-pulse-glow' }}">
                                                {{ $isFollowing ? 'Following' : 'Follow' }}
                                            </button>
                                        </form>
                                    @elseif(auth()->id() === $user->id)
                                        <a href="{{ route('profile.edit') }}" class="rounded-xl px-6 py-3 text-sm font-bold bg-surface-container text-on-surface border border-outline-variant/40 hover:bg-surface-container-high transition-all">
                                            Edit Profile
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <!-- Stats Grid -->
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 bg-surface-container-low/40 rounded-2xl border border-outline-variant/20">
                                <div class="text-center p-2">
                                    <div class="text-headline-md text-primary-container font-black">{{ number_format($user->points ?? 0) }}</div>
                                    <div class="text-label-sm uppercase tracking-wider text-on-surface-variant font-bold">Points</div>
                                </div>
                                <div class="text-center p-2 border-l border-outline-variant/10">
                                    <div class="text-headline-md text-on-surface font-black">{{ $user->followers_count }}</div>
                                    <div class="text-label-sm uppercase tracking-wider text-on-surface-variant font-bold">Followers</div>
                                </div>
                                <div class="text-center p-2 border-l border-outline-variant/10">
                                    <div class="text-headline-md text-on-surface font-black">{{ $user->following_count }}</div>
                                    <div class="text-label-sm uppercase tracking-wider text-on-surface-variant font-bold">Following</div>
                                </div>
                                <div class="text-center p-2 border-l border-outline-variant/10">
                                    <div class="text-headline-md text-on-surface font-black">{{ $user->posts_count }}</div>
                                    <div class="text-label-sm uppercase tracking-wider text-on-surface-variant font-bold">Posts</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Column -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Digital Identity Card -->
                    <div class="glass-card rounded-2xl p-6 glass-edge relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-5">
                            <span class="text-6xl">⚽</span>
                        </div>
                        <h3 class="text-headline-md text-on-surface mb-6 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary text-sm">✨</span>
                            Digital Identity
                        </h3>

                        @if($user->bio)
                            <div class="mb-8 p-5 bg-surface-container-high/30 rounded-xl border-l-4 border-primary-container">
                                <p class="text-body-lg text-on-surface italic leading-relaxed">"{{ $user->bio }}"</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($user->football_personality)
                                <div class="p-4 rounded-xl bg-surface-container/50 border border-outline-variant/20 flex items-center gap-4">
                                    <div class="text-2xl">🎭</div>
                                    <div>
                                        <div class="text-label-sm text-on-surface-variant uppercase font-bold tracking-tighter">Personality</div>
                                        <div class="text-body-md font-bold text-on-surface">{{ $user->football_personality }}</div>
                                    </div>
                                </div>
                            @endif

                            @if($user->country)
                                <div class="p-4 rounded-xl bg-surface-container/50 border border-outline-variant/20 flex items-center gap-4">
                                    <div class="text-2xl">📍</div>
                                    <div>
                                        <div class="text-label-sm text-on-surface-variant uppercase font-bold tracking-tighter">Location</div>
                                        <div class="text-body-md font-bold text-on-surface">{{ $user->country }}@if($user->state), {{ $user->state }}@endif</div>
                                    </div>
                                </div>
                            @endif

                            @if($user->favoritePlayer)
                                <div class="p-4 rounded-xl bg-surface-container/50 border border-outline-variant/20 flex items-center gap-4">
                                    @if($user->favoritePlayer->photo_url)
                                        <img src="{{ $user->favoritePlayer->photo_url }}" class="h-10 w-10 rounded-lg object-cover">
                                    @else
                                        <div class="text-2xl">👤</div>
                                    @endif
                                    <div>
                                        <div class="text-label-sm text-on-surface-variant uppercase font-bold tracking-tighter">Fav Player</div>
                                        <div class="text-body-md font-bold text-on-surface">{{ $user->favoritePlayer->name }}</div>
                                    </div>
                                </div>
                            @endif

                            @if($user->favorite_coach)
                                <div class="p-4 rounded-xl bg-surface-container/50 border border-outline-variant/20 flex items-center gap-4">
                                    <div class="text-2xl">🎓</div>
                                    <div>
                                        <div class="text-label-sm text-on-surface-variant uppercase font-bold tracking-tighter">Fav Coach</div>
                                        <div class="text-body-md font-bold text-on-surface">{{ $user->favorite_coach }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Posts Section -->
                    <div class="space-y-6">
                        <div class="flex items-center justify-between px-2">
                            <h2 class="text-headline-md text-on-surface flex items-center gap-3">
                                <span class="w-10 h-10 rounded-xl bg-primary-container/20 flex items-center justify-center text-primary-container text-xl">📝</span>
                                Match Talk
                            </h2>
                        </div>

                        @forelse($posts as $post)
                            <div class="glass-card rounded-2xl p-6 glass-edge hover:shadow-xl transition-all duration-300 group">
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="text-xs text-on-surface-variant font-bold uppercase tracking-widest">{{ $post->created_at->diffForHumans() }}</span>
                                    @if($post->community)
                                        <span class="text-outline-variant/40">·</span>
                                        <a href="{{ route('communities.show', $post->community) }}" class="text-xs font-black text-primary-container hover:underline">{{ $post->community->name }}</a>
                                    @endif
                                </div>

                                <a href="{{ route('posts.show', $post) }}" class="block group">
                                    <p class="text-body-lg text-on-surface leading-relaxed group-hover:text-primary-container transition-colors">{{ $post->body }}</p>
                                </a>

                                @if($post->media)
                                    <div class="mt-4 rounded-2xl overflow-hidden border border-outline-variant/20">
                                        <img loading="lazy" decoding="async" src="{{ data_get($post->media, '0.url') }}" alt="" class="w-full h-auto max-h-96 object-cover hover:scale-105 transition-transform duration-700">
                                    </div>
                                @endif

                                <div class="flex items-center gap-6 mt-6 pt-4 border-t border-outline-variant/10">
                                    <div class="flex items-center gap-2 text-primary-container font-black">
                                        <span class="text-xl">❤️</span>
                                        <span>{{ $post->likes_count }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-on-surface-variant font-bold">
                                        <span class="text-xl">💬</span>
                                        <span>{{ $post->comments_count }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-on-surface-variant font-bold">
                                        <span class="text-xl">🔁</span>
                                        <span>{{ $post->shares_count }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-3xl border-2 border-dashed border-outline-variant/20 p-16 text-center bg-surface-container/20">
                                <div class="text-6xl mb-4 opacity-20">⚽</div>
                                <p class="text-headline-md text-on-surface-variant font-bold">No match talk yet.</p>
                                <p class="text-body-md text-on-surface-variant mt-2">When {{ $user->name }} starts talking, it'll show up here.</p>
                            </div>
                        @endforelse
                        <div class="pt-4">{{ $posts->links() }}</div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-8">
                    <!-- Fan Ranking Sidebar Card -->
                    <div class="glass-card rounded-2xl p-6 glass-edge overflow-hidden group">
                        <div class="absolute -top-12 -right-12 h-32 w-32 bg-primary-container/10 rounded-full blur-2xl group-hover:bg-primary-container/20 transition-all duration-500"></div>
                        <h3 class="text-headline-md text-on-surface mb-6 flex items-center gap-2">
                            <span class="text-2xl">🏆</span>
                            Fan Status
                        </h3>

                        <div class="text-center mb-6">
                            @php($points = $user->points ?? 0)
                            <div class="text-headline-lg font-black tracking-tighter
                                @if($points >= 10000) text-primary-container
                                @elseif($points >= 5000) text-tertiary-fixed-dim
                                @elseif($points >= 2500) text-secondary
                                @elseif($points >= 1000) text-on-surface
                                @else text-on-surface-variant @endif">
                                @if($points >= 10000) LEGENDARY FAN
                                @elseif($points >= 5000) ELITE FAN
                                @elseif($points >= 2500) SUPER FAN
                                @elseif($points >= 1000) DIE-HARD FAN
                                @elseif($points >= 500) ACTIVE FAN
                                @elseif($points >= 100) RISING FAN
                                @else NEW FAN @endif
                            </div>
                            <div class="text-label-bold text-primary-container mt-1 font-black">{{ number_format($points) }} POINTS</div>

                            <!-- Rank Progress -->
                            @php($nextRankPoints = $user->getNextRankPoints())
@php($progress = $nextRankPoints > 0 ? (($user->points ?? 0) / $nextRankPoints) * 100 : 100)

                            @if($nextRankPoints > 0)
                                <div class="mt-6 text-left">
                                    <div class="flex justify-between text-label-sm text-on-surface-variant mb-2 font-bold uppercase tracking-wider">
                                        <span>Progress</span>
                                        <span>{{ number_format($nextRankPoints - ($user->points ?? 0)) }} more to go</span>
                                    </div>
                                    <div class="h-3 bg-surface-container-highest rounded-full overflow-hidden p-0.5 border border-outline-variant/20">
                                        <div class="h-full bg-primary-container rounded-full animate-pulse-glow" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Achievements -->
                    @if($user->badges->count())
                        <div class="glass-card rounded-2xl p-6 glass-edge">
                            <h3 class="text-headline-md text-on-surface mb-6 flex items-center gap-2">
                                <span class="text-2xl">🏅</span>
                                Collection
                            </h3>
                            <div class="grid grid-cols-4 gap-3">
                                @foreach($user->badges as $badge)
                                    <div class="aspect-square rounded-xl bg-surface-container-high/50 flex items-center justify-center border border-outline-variant/20 hover:scale-110 hover:border-primary-container transition-all cursor-help" title="{{ $badge->name }}: {{ $badge->description }}">
                                        <span class="text-2xl">{{ $badge->icon ?? '🏅' }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Favorite Clubs -->
                    @if($user->favoriteClubs->count())
                        <div class="glass-card rounded-2xl glass-edge overflow-hidden">
                            <div class="px-6 py-4 bg-surface-container/50 border-b border-outline-variant/20">
                                <h3 class="font-black text-sm text-on-surface uppercase tracking-widest flex items-center gap-2">
                                    <span class="text-primary-container">💚</span> Loyalty
                                </h3>
                            </div>
                            <div class="p-4 space-y-3">
                                @foreach($user->favoriteClubs as $club)
                                    <a href="{{ $club->api_team_id ? route('clubs.show', $club->api_team_id) : '#' }}"
                                       class="flex items-center gap-4 p-3 rounded-xl hover:bg-primary-container/10 transition-all border border-transparent hover:border-primary-container/30 group">
                                        @if($club->logo_url)
                                            <img loading="lazy" decoding="async" src="{{ $club->logo_url }}" alt="" class="w-10 h-10 object-contain group-hover:scale-110 transition-transform">
                                        @else
                                            <div class="w-10 h-10 rounded-lg bg-surface-container-high flex items-center justify-center text-xl">⚽</div>
                                        @endif
                                        <div class="font-bold text-on-surface group-hover:text-primary-container transition-colors">{{ $club->name }}</div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Communities -->
                    @if($user->communities->count())
                        <div class="glass-card rounded-2xl glass-edge overflow-hidden">
                            <div class="px-6 py-4 bg-surface-container/50 border-b border-outline-variant/20">
                                <h3 class="font-black text-sm text-on-surface uppercase tracking-widest flex items-center gap-2">
                                    <span class="text-primary-container">👥</span> Circles
                                </h3>
                            </div>
                            <div class="p-4 space-y-2">
                                @foreach($user->communities as $community)
                                    <a href="{{ route('communities.show', $community) }}"
                                       class="block p-3 rounded-xl text-sm font-bold text-on-surface-variant hover:text-primary-container hover:bg-primary-container/5 transition-all truncate border-l-2 border-transparent hover:border-primary-container pl-4">
                                        {{ $community->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Report -->
                    @if(auth()->check() && auth()->id() !== $user->id)
                        <div class="px-2">
                            <details class="group">
                                <summary class="text-xs text-on-surface-variant font-bold cursor-pointer hover:text-error transition-colors uppercase tracking-widest list-none flex items-center gap-2">
                                    <span class="opacity-50">⚠️</span>
                                    <span>Report User</span>
                                </summary>
                                <div class="mt-4 p-4 glass-card rounded-xl border-error/20">
                                    <form action="{{ route('reports.store') }}" method="POST" class="space-y-4">
                                        @csrf
                                        <input type="hidden" name="reportable_type" value="user">
                                        <input type="hidden" name="reportable_id" value="{{ $user->id }}">
                                        <select name="reason" required class="w-full rounded-xl border-outline-variant/20 bg-surface-container text-on-surface text-sm focus:border-primary focus:ring-primary/20">
                                            <option value="">Why report?</option>
                                            <option value="spam">Spamming</option>
                                            <option value="harassment">Toxic behavior</option>
                                            <option value="fake_account">Fake account</option>
                                            <option value="other">Other</option>
                                        </select>
                                        <textarea name="description" rows="2" placeholder="Details..." class="w-full rounded-xl border-outline-variant/20 bg-surface-container text-on-surface text-sm focus:border-primary focus:ring-primary/20"></textarea>
                                        <button type="submit" class="w-full rounded-xl bg-error/10 border border-error/20 py-2 text-xs font-black text-error hover:bg-error/20 transition-all uppercase tracking-widest">Submit</button>
                                    </form>
                                </div>
                            </details>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
