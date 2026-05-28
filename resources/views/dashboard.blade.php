<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-2 sm:p-3 md:p-4">

        {{-- Welcome Banner --}}
        <div
            class="relative rounded-2xl overflow-hidden bg-gradient-stadium-primary p-4 sm:p-6 md:p-8 text-on-primary shadow-xl shadow-primary/20 glow-primary-lg">

            {{-- share modal --}}
            <flux:modal name="share-post" class="min-w-[300px] md:min-w-[450px]">
                <div class="space-y-6">
                    <div>
                        <flux:heading size="lg">Share Football Talk</flux:heading>
                        <flux:subheading>Pass this on to other fans or share it to your socials.</flux:subheading>
                    </div>

                    <div class="grid grid-cols-4 gap-4 py-2">
                        <button
                            @click="window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent('Check out this football talk: ') + '&url=' + encodeURIComponent($wire.sharingPostUrl), '_blank')"
                            class="flex flex-col items-center gap-2 group">
                            <div
                                class="w-12 h-12 rounded-full bg-zinc-900 flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-on-surface-variant">X / Twitter</span>
                        </button>

                        <button
                            @click="window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent($wire.sharingPostUrl), '_blank')"
                            class="flex flex-col items-center gap-2 group">
                            <div
                                class="w-12 h-12 rounded-full bg-[#1877F2] flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-on-surface-variant">Facebook</span>
                        </button>

                        <button
                            @click="window.open('https://wa.me/?text=' + encodeURIComponent('Check out this football talk: ' + $wire.sharingPostUrl), '_blank')"
                            class="flex flex-col items-center gap-2 group">
                            <div
                                class="w-12 h-12 rounded-full bg-[#25D366] flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-on-surface-variant">WhatsApp</span>
                        </button>

                        <button
                            @click="navigator.clipboard.writeText($wire.sharingPostUrl); alert('Link copied to clipboard!')"
                            class="flex flex-col items-center gap-2 group">
                            <div
                                class="w-12 h-12 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface group-hover:scale-110 transition-transform">
                                <flux:icon icon="link" />
                            </div>
                            <span class="text-xs font-medium text-on-surface-variant">Copy Link</span>
                        </button>
                    </div>

                    <flux:input x-ref="shareUrl" readonly x-bind:value="$wire.sharingPostUrl" icon="link"
                        class="bg-surface-container/50">
                        <x-slot name="append">
                            <flux:button variant="ghost" size="sm"
                                @click="navigator.clipboard.writeText($wire.sharingPostUrl); alert('Copied!')">Copy
                            </flux:button>
                        </x-slot>
                    </flux:input>

                    <div class="flex justify-end pt-2">
                        <flux:modal.close>
                            <flux:button variant="ghost">Close</flux:button>
                        </flux:modal.close>
                    </div>
                </div>
            </flux:modal>
            {{-- end share modal --}}

            <div class="absolute inset-0 opacity-10"
                style="background-image: url('https://images.unsplash.com/photo-1508098682722-e99c643e7f0b?w=600&q=30'); background-size: cover; background-position: center;">
            </div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4">
            </div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/4">
            </div>
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-h2-mobile sm:text-h1 font-bold mb-2 text-on-primary">⚽ Your Football Hub</h1>
                    <p class="text-on-primary/70 text-sm sm:text-base font-medium">{{ auth()->user()->name }}, your
                        personalized football community awaits!</p>
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <div
                        class="glass glow-primary rounded-xl px-4 py-3 text-center min-w-[80px] hover:scale-105 transition-transform">
                        <div class="text-2xl font-bold text-primary">{{ auth()->user()->points ?? 0 }}</div>
                        <div class="text-xs text-on-primary/70 uppercase font-semibold">Points</div>
                    </div>
                    <div
                        class="glass glow-primary rounded-xl px-4 py-3 text-center min-w-[80px] hover:scale-105 transition-transform">
                        <div class="text-2xl font-bold text-primary">{{ auth()->user()->badges()->count() }}</div>
                        <div class="text-xs text-on-primary/70 uppercase font-semibold">Badges</div>
                    </div>
                    <div
                        class="glass glow-primary rounded-xl px-4 py-3 text-center min-w-[80px] hover:scale-105 transition-transform">
                        <div class="text-2xl font-bold text-secondary">{{ auth()->user()->following()->count() }}</div>
                        <div class="text-xs text-on-primary/70 uppercase font-semibold">Following</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Live Matches Banner --}}
        <livewire:matches.live lazy />

        {{-- FAN HUB SECTION: Your Matches & Clubs --}}
        <div class="grid gap-6 md:grid-cols-2">
            {{-- Your Clubs Section --}}
            @if ($favoriteClubs->isNotEmpty())
                <div
                    class="card rounded-2xl border border-primary/25 bg-gradient-to-b from-primary/8 to-primary/4 p-6 shadow-sm glass-premium hover:glow-primary-sm transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <span class="text-3xl">💚</span>
                            <div>
                                <h3 class="text-h6 font-bold text-on-surface uppercase tracking-wide">Your Clubs</h3>
                                <p class="text-xs text-on-surface-variant font-medium">All teams you follow</p>
                            </div>
                        </div>
                        <span class="text-xs font-semibold text-on-surface-variant">{{ $favoriteClubs->count() }}
                            clubs</span>
                    </div>
                    <div class="grid gap-3">
                        @foreach ($favoriteClubs as $club)
                            @php $isPrimaryClub = (bool) ($club->pivot->is_primary ?? false); @endphp
                            <a href="{{ $club->api_team_id ? route('clubs.show', $club->api_team_id) : '#' }}"
                                class="flex items-center gap-4 p-4 rounded-xl bg-gradient-to-r from-primary/15 to-primary/10 border border-primary/20 hover:from-primary/25 hover:to-primary/15 transition-all group cursor-pointer">
                                @if ($club->logo_url)
                                    <img src="{{ $club->logo_url }}" alt="{{ $club->name }}"
                                        class="w-12 h-12 object-contain group-hover:scale-110 transition-transform">
                                @else
                                    <div
                                        class="w-12 h-12 rounded-xl bg-surface-container-high flex items-center justify-center font-bold text-on-surface">
                                        {{ strtoupper(substr($club->name, 0, 2)) }}
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <div class="font-bold text-on-surface truncate">{{ $club->name }}</div>
                                        @if ($isPrimaryClub)
                                            <span
                                                class="inline-flex items-center rounded-full bg-primary/20 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-primary">Primary</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-on-surface-variant font-medium truncate">
                                        {{ $club->country ?? 'Your favorite team' }}</div>
                                </div>
                                <span class="text-primary hover:text-primary/80 transition-colors">→</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Your Upcoming Matches --}}
                <div
                    class="card rounded-2xl border border-secondary/25 bg-gradient-to-b from-secondary/8 to-secondary/4 p-6 shadow-sm glass-premium hover:glow-primary-sm transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <span class="text-3xl">📅</span>
                            <div>
                                <h3 class="text-h6 font-bold text-on-surface uppercase tracking-wide">Next Matches</h3>
                                <p class="text-xs text-on-surface-variant font-medium">Your team's fixtures</p>
                            </div>
                        </div>
                        <a href="{{ route('matches.index') }}"
                            class="focus-ring text-primary hover:text-primary/80 font-bold text-sm transition-colors">View
                            All</a>
                    </div>
                    <livewire:matches.upcoming lazy />
                </div>
            @endif
        </div>

        {{-- MAIN CONTENT GRID --}}
        <div class="grid gap-6 lg:gap-8 lg:grid-cols-3">
            {{-- Main Feed --}}
            <div class="lg:col-span-2 space-y-4 md:space-y-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-h5 font-bold text-on-surface flex items-center gap-2 uppercase tracking-wide">
                        <span
                            class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary/20 to-primary/10 flex items-center justify-center text-primary text-lg glow-primary">📰</span>
                        Community Feed
                    </h2>
                    <a href="{{ route('feed') }}"
                        class="focus-ring text-sm text-primary hover:text-primary/80 font-semibold hover:underline hover:gap-2 inline-flex items-center transition-all">View
                        All <span class="ml-1">→</span></a>
                </div>

                {{-- Create Post --}}
                <livewire:posts.composer mode="dashboard" :redirect-after-submit="true" :key="'dashboard-post-composer'" />

                {{-- Feed Posts --}}
                <!-- Feed Posts -->
                <div class="space-y-4" id="feed-stream">
                    @forelse($feed as $post)
                        <div class="glass-card rounded-xl p-5 hover:bg-surface-container/50 transition-colors">
                            <!-- Post Header -->
                            <div class="flex items-start gap-3">
                                <a href="{{ route('profiles.show', $post->user) }}" class="flex-shrink-0">
                                    @if ($post->user->avatar_url)
                                        <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}"
                                            class="h-10 w-10 rounded-lg object-cover">
                                    @else
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-surface-container-high text-sm font-bold text-on-surface">
                                            {{ substr($post->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                </a>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <a href="{{ route('profiles.show', $post->user) }}"
                                            class="text-label-bold text-on-surface hover:text-primary-container transition-colors">
                                            {{ $post->user->name }}
                                        </a>
                                        <!-- Content Type Badge -->
                                        @if ($post->type && $post->type !== 'text')
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-primary-container/20 text-primary-container text-label-sm">
                                                {{ $postTypes[$post->type]['icon'] ?? '📰' }}
                                                {{ $postTypes[$post->type]['label'] ?? $post->type }}
                                            </span>
                                        @endif
                                        @if ($post->community)
                                            <span class="text-label-sm text-on-surface-variant">in</span>
                                            <a href="{{ route('communities.show', $post->community) }}"
                                                class="inline-flex items-center gap-1 text-label-bold text-primary-container hover:underline">
                                                {{ $post->community->name }}
                                            </a>
                                        @endif
                                        <span
                                            class="text-label-sm text-on-surface-variant">{{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Post Body -->
                            <div class="mt-3">
                                <p class="text-body-md text-on-surface leading-relaxed whitespace-pre-line">
                                    {{ $post->body }}</p>
                            </div>

                            <!-- Media -->
                            @if ($post->media)
                                <div
                                    class="mt-3 grid gap-2 {{ count($post->media) > 1 ? 'grid-cols-2' : '' }} rounded-xl overflow-hidden">
                                    @foreach ($post->media as $media)
                                        <img loading="lazy" src="{{ $media['url'] }}" alt=""
                                            class="rounded-xl max-h-72 w-full object-cover hover:opacity-90 transition-opacity">
                                    @endforeach
                                </div>
                            @endif

                            <!-- Engagement Bar -->
                            <div
                                class="mt-4 pt-3 border-t border-outline-variant/20 flex items-center gap-1 flex-wrap">
                                <livewire:reactions.bar target-type="post" :target-id="$post->id" :key="'dashboard-post-reactions-' . $post->id" />

                                <!-- Comment -->
                                <a href="{{ route('posts.show', $post) }}"
                                    class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-label-sm font-medium text-on-surface-variant hover:text-primary-container hover:bg-primary-container/5 transition-all">
                                    <span class="text-base">💬</span>
                                    <span>{{ $post->comments_count }}</span>
                                </a>

                                <!-- Share -->
                                <button wire:click="sharePost({{ $post->id }})"
                                    class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-label-sm font-medium text-on-surface-variant hover:text-primary-container hover:bg-primary-container/5 transition-all">
                                    <span class="text-base">🔗</span>
                                    <span>{{ $post->shares_count }}</span>
                                </button>

                                <!-- Quick Reactions -->
                                <div class="hidden sm:flex items-center gap-1 ml-auto">
                                    <button class="hover:scale-125 transition-transform text-lg"
                                        title="Fire">🔥</button>
                                    <button class="hover:scale-125 transition-transform text-lg"
                                        title="Love">💚</button>
                                    <button class="hover:scale-125 transition-transform text-lg"
                                        title="LOL">😂</button>
                                    <button class="hover:scale-125 transition-transform text-lg"
                                        title="Shock">😱</button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="glass-card rounded-xl p-8 text-center">
                            <div class="text-4xl mb-4">⚽</div>
                            <h3 class="text-headline-md text-on-surface mb-2">Your feed is empty</h3>
                            <p class="text-body-md text-on-surface-variant mb-4">Follow users or join communities to
                                see football banter here!</p>
                            <a href="{{ route('communities.index') }}"
                                class="inline-flex items-center gap-2 rounded-xl bg-primary-container px-5 py-2.5 text-sm font-semibold text-on-primary-container hover:bg-primary-container/90 transition-all">
                                Explore Communities →
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-4 md:space-y-5">
                {{-- Achievements Section --}}
                <div
                    class="card rounded-2xl border border-secondary/25 bg-gradient-to-b from-secondary/8 to-secondary/4 p-6 shadow-sm glass-premium hover:glow-primary-sm transition-all card-entrance">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-2xl celebrate">🏆</span>
                        <h3 class="font-bold text-h6 text-on-surface uppercase tracking-wide">Your Streak</h3>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div
                            class="flex items-center justify-between p-2.5 rounded-lg bg-gradient-to-r from-secondary/15 to-secondary/10 border border-secondary/20 hover:from-secondary/25 hover:to-secondary/15 transition-all smooth-color-transition card-hover-lift">
                            <div class="flex items-center gap-2">
                                <span class="text-xl pulse-energy">🔥</span>
                                <span class="text-on-surface-variant font-semibold text-xs md:text-sm">Current
                                    Streak</span>
                            </div>
                            <span class="font-bold text-secondary text-base md:text-lg">7d</span>
                        </div>
                        <div
                            class="flex items-center justify-between p-2.5 rounded-lg bg-primary/10 border border-primary/15 hover:bg-primary/15 transition-all smooth-color-transition card-hover-lift">
                            <span class="text-on-surface-variant font-semibold text-xs md:text-sm">Posts This
                                Week</span>
                            <span
                                class="font-bold text-primary text-base md:text-lg">{{ auth()->user()->posts()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count() }}</span>
                        </div>
                        <div
                            class="flex items-center justify-between p-2.5 rounded-lg bg-tertiary/10 border border-tertiary/15 hover:bg-tertiary/15 transition-all smooth-color-transition card-hover-lift">
                            <span class="text-on-surface-variant font-semibold text-xs md:text-sm">Total Posts</span>
                            <span
                                class="font-bold text-tertiary text-base md:text-lg">{{ auth()->user()->posts()->count() }}</span>
                        </div>
                        <a href="{{ route('leaderboard') }}"
                            class="focus-ring inline-flex items-center gap-1 text-primary hover:text-primary/80 font-bold text-xs mt-2 transition-colors hover:underline">
                            View All Badges →
                        </a>
                    </div>
                </div>

                {{-- Fan Streaks Leaderboard --}}
                <div
                    class="card rounded-2xl border border-tertiary/25 bg-gradient-to-b from-surface-container/80 to-surface-container/40 overflow-hidden shadow-sm glass-premium hover:glow-primary transition-all card-entrance">
                    <div class="px-5 py-4 bg-gradient-to-r from-tertiary/15 to-tertiary/8 border-b border-tertiary/20">
                        <h3 class="font-bold text-h6 text-on-surface flex items-center gap-2 uppercase tracking-wide">
                            <span class="text-lg">⚡</span> Top Streaks
                        </h3>
                    </div>
                    <div class="p-2.5 space-y-1">
                        <div
                            class="flex items-center justify-between p-1.5 rounded-lg hover:bg-tertiary/5 transition-all smooth-color-transition card-hover-lift stagger-1">
                            <div class="flex items-center gap-2 min-w-0 flex-1">
                                <span class="font-bold text-tertiary min-w-[20px] text-center text-sm">1</span>
                                <span class="text-xs md:text-sm font-medium text-on-surface truncate">You</span>
                            </div>
                            <span class="font-bold text-tertiary text-xs md:text-sm ml-1 flex-shrink-0">7🔥</span>
                        </div>
                        <div
                            class="flex items-center justify-between p-1.5 rounded-lg hover:bg-tertiary/5 transition-all smooth-color-transition card-hover-lift stagger-2">
                            <div class="flex items-center gap-2 min-w-0 flex-1">
                                <span
                                    class="font-bold text-on-surface-variant min-w-[20px] text-center text-sm">2</span>
                                <span
                                    class="text-xs md:text-sm font-medium text-on-surface-variant truncate">Marcus</span>
                            </div>
                            <span
                                class="font-bold text-on-surface-variant text-xs md:text-sm ml-1 flex-shrink-0">12🔥</span>
                        </div>
                        <div
                            class="flex items-center justify-between p-1.5 rounded-lg hover:bg-tertiary/5 transition-all smooth-color-transition card-hover-lift stagger-3">
                            <div class="flex items-center gap-2 min-w-0 flex-1">
                                <span
                                    class="font-bold text-on-surface-variant min-w-[20px] text-center text-sm">3</span>
                                <span
                                    class="text-xs md:text-sm font-medium text-on-surface-variant truncate">Sarah</span>
                            </div>
                            <span
                                class="font-bold text-on-surface-variant text-xs md:text-sm ml-1 flex-shrink-0">5🔥</span>
                        </div>
                        <a href="{{ route('leaderboard') }}"
                            class="focus-ring inline-flex items-center gap-1 text-tertiary hover:text-tertiary/80 font-bold text-xs mt-2 transition-colors hover:underline">
                            Full Leaderboard →
                        </a>
                    </div>
                </div>

                {{-- Active Polls --}}
                <div
                    class="card rounded-2xl border border-tertiary/25 bg-gradient-to-b from-surface-container/80 to-surface-container/40 overflow-hidden shadow-sm glass-premium hover:glow-primary transition-all">
                    <div class="px-5 py-4 bg-gradient-to-r from-tertiary/15 to-tertiary/8 border-b border-tertiary/20">
                        <h3 class="font-bold text-h6 text-on-surface flex items-center gap-2 uppercase tracking-wide">
                            <span class="text-lg">📊</span> Active Polls
                        </h3>
                    </div>
                    <div class="p-2.5 space-y-1.5">
                        @forelse($activePolls as $poll)
                            <a href="{{ route('polls.show', $poll) }}"
                                class="block p-2 rounded-xl hover:bg-tertiary/5 dark:hover:bg-tertiary/10 transition-all duration-200">
                                <div class="text-xs md:text-sm font-medium text-on-surface line-clamp-1">
                                    {{ $poll->title }}</div>
                                <div class="flex justify-between text-xs text-on-surface-variant mt-1">
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        {{ $poll->total_votes }}
                                    </span>
                                    @if ($poll->closes_at)
                                        <span class="truncate">{{ $poll->closes_at->diffForHumans() }}</span>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <p class="text-xs md:text-sm text-on-surface-variant p-2 text-center">No polls.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Sidebar Ad --}}
                <x-ad-unit placement="sidebar" />

                {{-- Trending Posts --}}
                <div
                    class="card rounded-2xl border border-secondary/25 bg-gradient-to-b from-surface-container/80 to-surface-container/40 overflow-hidden shadow-sm glass-premium hover:glow-primary transition-all">
                    <div
                        class="px-5 py-4 bg-gradient-to-r from-secondary/15 to-secondary/8 border-b border-secondary/20">
                        <h3 class="font-bold text-h6 text-on-surface flex items-center gap-2 uppercase tracking-wide">
                            <span class="text-lg">🔥</span> Trending
                        </h3>
                    </div>
                    <div class="p-2.5 space-y-1.5">
                        @forelse($trendingPosts as $post)
                            <a href="{{ route('posts.show', $post) }}"
                                class="block p-2 rounded-xl hover:bg-secondary/5 dark:hover:bg-secondary/10 transition-all duration-200">
                                <div class="text-xs md:text-sm text-on-surface line-clamp-2 leading-snug">
                                    {{ Str::limit($post->body, 60) }}</div>
                                <div class="flex items-center gap-1.5 mt-1.5 text-xs text-on-surface-variant">
                                    <span class="font-medium truncate">{{ Str::limit($post->user->name, 12) }}</span>
                                    <span class="text-outline-variant/40">·</span>
                                    <span class="inline-flex items-center gap-0.5 text-primary flex-shrink-0">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                        {{ $post->likes_count }}
                                    </span>
                                </div>
                            </a>
                        @empty
                            <p class="text-xs md:text-sm text-on-surface-variant p-2 text-center">Nothing trending.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Mini League Table --}}
                <div
                    class="card rounded-2xl border border-primary/25 bg-gradient-to-b from-primary/8 to-primary/4 overflow-hidden shadow-sm glass-premium hover:glow-primary-sm transition-all card-entrance">
                    <div class="px-5 py-4 bg-gradient-to-r from-primary/15 to-primary/8 border-b border-primary/20">
                        <h3 class="font-bold text-h6 text-on-surface flex items-center gap-2 uppercase tracking-wide">
                            <span class="text-lg">⚽</span> Points Race
                        </h3>
                    </div>
                    <div class="p-2.5 space-y-1">
                        <div
                            class="flex items-center justify-between p-1.5 rounded-lg bg-gradient-to-r from-primary/10 to-primary/5 border border-primary/20 smooth-color-transition card-hover-lift achievement-unlock">
                            <div class="flex items-center gap-2 flex-1 min-w-0">
                                <span class="font-bold text-base md:text-lg">🥇</span>
                                <span class="text-xs md:text-sm font-medium text-on-surface truncate">You</span>
                            </div>
                            <div class="flex items-center gap-1 ml-1 flex-shrink-0">
                                <span
                                    class="font-bold text-primary text-xs md:text-sm">{{ auth()->user()->points ?? 0 }}</span>
                            </div>
                        </div>
                        <div
                            class="flex items-center justify-between p-1.5 rounded-lg hover:bg-primary/5 transition-all smooth-color-transition card-hover-lift stagger-1">
                            <div class="flex items-center gap-2 flex-1 min-w-0">
                                <span class="font-bold text-base md:text-lg">🥈</span>
                                <span
                                    class="text-xs md:text-sm font-medium text-on-surface-variant truncate">James</span>
                            </div>
                            <div class="flex items-center gap-1 ml-1 flex-shrink-0">
                                <span class="font-bold text-on-surface-variant text-xs md:text-sm">1,240</span>
                            </div>
                        </div>
                        <div
                            class="flex items-center justify-between p-1.5 rounded-lg hover:bg-primary/5 transition-all smooth-color-transition card-hover-lift stagger-2">
                            <div class="flex items-center gap-2 flex-1 min-w-0">
                                <span class="font-bold text-base md:text-lg">🥉</span>
                                <span
                                    class="text-xs md:text-sm font-medium text-on-surface-variant truncate">Alex</span>
                            </div>
                            <div class="flex items-center gap-1 ml-1 flex-shrink-0">
                                <span class="font-bold text-on-surface-variant text-xs md:text-sm">980</span>
                            </div>
                        </div>
                        <a href="{{ route('leaderboard') }}"
                            class="focus-ring inline-flex items-center gap-1 text-primary hover:text-primary/80 font-bold text-xs mt-2 transition-colors hover:underline">
                            Full Rankings →
                        </a>
                    </div>
                </div>

                {{-- Your Communities --}}
                @php $userCommunities = auth()->user()->communities()->limit(5)->get(); @endphp
                @if ($userCommunities->isNotEmpty())
                    <div
                        class="rounded-2xl border border-primary/20 bg-gradient-to-b from-surface-container/80 to-surface-container/40 overflow-hidden shadow-sm glass-premium hover:glow-primary-sm transition-all">
                        <div
                            class="px-5 py-4 bg-gradient-to-r from-primary/15 to-primary/8 border-b border-primary/20">
                            <h3
                                class="font-bold text-h6 text-on-surface flex items-center gap-2 uppercase tracking-wide">
                                <span class="text-lg">👥</span> Your Communities
                            </h3>
                        </div>
                        <div class="p-2.5 space-y-1.5">
                            @foreach ($userCommunities as $community)
                                <a href="{{ route('communities.show', $community) }}"
                                    class="block p-1.5 rounded-lg hover:bg-primary/10 dark:hover:bg-primary/15 transition-all duration-200">
                                    <div class="text-xs md:text-sm font-medium text-on-surface truncate">
                                        {{ $community->name }}</div>
                                    <div class="text-xs text-on-surface-variant">{{ $community->members_count ?? 0 }}
                                        members</div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts::app>
