<div wire:poll.60s class="min-h-screen bg-surface py-6">
    <!-- Stadium Glow Background -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-1/4 left-0 h-96 w-96 rounded-full bg-primary-container/3 blur-3xl"></div>
        <div class="absolute bottom-1/4 right-0 h-96 w-96 rounded-full bg-secondary/5 blur-3xl"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Community Header -->
        <div class="glass-card rounded-xl p-6 mb-6 relative overflow-hidden">
            @if($community->banner_url)
                <img src="{{ $community->banner_url }}" alt="" class="absolute inset-0 w-full h-full object-cover opacity-20">
            @endif
            <div class="absolute -top-20 -right-20 h-40 w-40 rounded-full bg-primary-container/10 blur-3xl"></div>
            <div class="relative z-10 flex flex-col md:flex-row gap-6 items-start">
                <!-- Community Avatar -->
                <div class="flex-shrink-0">
                    @if($community->avatar_url)
                        <img src="{{ $community->avatar_url }}" alt="{{ $community->name }}"
                             class="h-20 w-20 rounded-2xl object-cover border-2 border-outline-variant/40">
                    @else
                        <div class="h-20 w-20 rounded-2xl bg-surface-container-high flex items-center justify-center text-3xl font-bold text-on-surface">
                            {{ substr($community->name, 0, 2) }}
                        </div>
                    @endif
                </div>

                <!-- Community Info -->
                <div class="flex-1">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                        <div>
                            <h1 class="text-headline-lg text-on-surface">{{ $community->name }}</h1>
                            @if($community->club)
                                <p class="text-label-bold text-primary-container mt-1">{{ $community->club->name }}</p>
                            @endif
                            @if($community->country)
                                <p class="text-label-sm text-on-surface-variant mt-2">
                                    📍
                                    {{ $community->country }}
                                    @if($community->state), {{ $community->state }}@endif
                                    @if($community->region) • {{ $community->region }}@endif
                                </p>
                            @endif
                        </div>
                        @if(auth()->check())
                            <button wire:click="join()"
                                    class="px-6 py-2.5 rounded-xl text-sm font-semibold transition-all
                                            {{ $isMember
                                                ? 'bg-surface-container-high text-on-surface hover:bg-red-500/20 hover:text-red-400'
                                                : 'bg-primary-container text-on-primary-container hover:bg-primary-container/90' }}">
                                {{ $isMember ? 'Leave Community' : 'Join Community' }}
                            </button>
                        @endif
                    </div>
                    <p class="mt-3 text-body-md text-on-surface">{{ $community->description }}</p>
                    <div class="mt-4 flex gap-4 text-label-sm text-on-surface-variant">
                        <span class="flex items-center gap-1">👥 {{ $community->members_count }} members</span>
                        <span class="flex items-center gap-1">📰 {{ $community->posts_count }} posts</span>
                        @if($isModerator)
                            <span class="flex items-center gap-1 px-2 py-0.5 rounded-full bg-primary-container/20 text-primary-container">Moderator</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main: Discussions -->
            <div class="lg:col-span-2 space-y-4">
                @if($isMember)
                    <div class="mb-6">
                        <livewire:posts.composer :community-id="$community->id" mode="community" />
                    </div>
                @endif

                <h2 class="text-headline-md text-on-surface flex items-center gap-2">
                    <span>💬</span> Discussions
                </h2>

                @forelse($discussions as $post)
                    <div class="glass-card rounded-xl p-5 hover:bg-surface-container/50 transition-colors">
                        <div class="flex items-start gap-3">
                            @if($post->user->avatar_url)
                                <img src="{{ $post->user->avatar_url }}" alt="" class="h-10 w-10 rounded-lg object-cover">
                            @else
                                <div class="h-10 w-10 rounded-lg bg-surface-container-high flex items-center justify-center text-xs font-bold text-on-surface">
                                    {{ substr($post->user->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('profiles.show', $post->user) }}" class="text-label-bold text-on-surface hover:text-primary-container">{{ $post->user->name }}</a>
                                    <span class="text-label-sm text-on-surface-variant">{{ $post->created_at->diffForHumans() }}</span>
                                </div>
                                <a href="{{ route('posts.show', $post) }}" class="block mt-2">
                                    <p class="text-body-md text-on-surface leading-relaxed">{{ Str::limit($post->body, 200) }}</p>
                                </a>
                                <div class="mt-3 flex items-center gap-4 text-label-sm">
                                    <span class="flex items-center gap-1 text-on-surface-variant">♥ {{ $post->likes_count }}</span>
                                    <span class="flex items-center gap-1 text-on-surface-variant">💬 {{ $post->comments_count }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="glass-card rounded-xl p-8 text-center">
                        <div class="text-4xl mb-4">💬</div>
                        <h3 class="text-headline-md text-on-surface mb-2">No discussions yet</h3>
                        <p class="text-body-md text-on-surface-variant">Be the first to start a discussion in this community!</p>
                    </div>
                @endforelse
            </div>

            <!-- Sidebar -->
            <div class="space-y-5">
                @if($isModerator)
                    <div class="glass-card rounded-xl p-4 border border-primary-container/20">
                        <h3 class="text-label-bold text-on-surface mb-3 flex items-center gap-2">
                            <span>🛡️</span> Moderator Tools
                        </h3>
                        <div class="space-y-2 text-sm">
                            <button class="w-full px-4 py-2 rounded-lg bg-surface-container-high text-on-surface font-medium hover:bg-surface-container transition-colors text-left flex items-center gap-2">
                                <span>✏️</span> Edit Community
                            </button>
                            <button class="w-full px-4 py-2 rounded-lg bg-surface-container-high text-on-surface font-medium hover:bg-surface-container transition-colors text-left flex items-center gap-2">
                                <span>👥</span> Manage Members
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Top Members -->
                <div class="glass-card rounded-xl p-4">
                    <h3 class="text-label-bold text-on-surface mb-3 flex items-center gap-2">
                        <span>🏆</span> Top Members
                    </h3>
                    <div class="space-y-2">
                        @foreach($topMembers as $index => $member)
                            <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-surface-container/30 transition-colors">
                                <span class="text-label-bold text-on-surface-variant w-5">{{ $index + 1 }}</span>
                                @if($member->avatar_url)
                                    <img src="{{ $member->avatar_url }}" alt="" class="h-8 w-8 rounded-lg object-cover">
                                @else
                                    <div class="h-8 w-8 rounded-lg bg-surface-container-high flex items-center justify-center text-xs font-bold text-on-surface">
                                        {{ substr($member->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('profiles.show', $member) }}" class="text-label-bold text-on-surface truncate">{{ $member->name }}</a>
                                    <p class="text-label-sm text-on-surface-variant">{{ $member->posts_count }} posts</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Community Stats -->
                <div class="glass-card rounded-xl p-4">
                    <h3 class="text-label-bold text-on-surface mb-3">Community Stats</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-on-surface-variant">Total Members</span>
                            <span class="text-on-surface font-bold">{{ $community->members_count }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-on-surface-variant">Total Posts</span>
                            <span class="text-on-surface font-bold">{{ $community->posts_count }}</span>
                        </div>
                        @if($community->club)
                            <div class="flex justify-between">
                                <span class="text-on-surface-variant">Club</span>
                                <a href="{{ route('clubs.show', $community->club->api_team_id ?? $community->club->id) }}" class="text-primary-container hover:underline">{{ $community->club->name }}</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
